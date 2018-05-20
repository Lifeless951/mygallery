<?php

namespace MyGallery\Libraries\Authentication;


class MyPDOSessionHandler implements \SessionHandlerInterface, \SessionUpdateTimestampHandlerInterface
{
    /**
     * @var \PDO
     */
    private $db;
    
    /**
     * Будет ли использован механизм транзакций
     * @var bool
     */
    private $useTransaction;
    
    /**
     * Массив объектов типа PDOStatement с подготовленным
     * запросом снятия явной блокировки
     * @var array
     */
    private $releaseStatements = [];
    
    private $sessTable = 'sessions';
    private $sidColumn = 'sid';
    private $expiryColumn = 'expiry';
    private $dataColumn = 'data';
    
    function __construct(\PDO $db, bool $useTransaction = true)
    {
        if ( $db->getAttribute(\PDO::ATTR_ERRMODE) !== \PDO::ERRMODE_EXCEPTION ) {
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        $this->db = $db;
        $this->useTransaction = $useTransaction;
    }
    
    /**
     * Повторно инициализирует существующую сессию или создает новую.
     * @return bool
     */
    public function open($save_path, $name)
    {
        return true;
    }
    
    /**
     * Закрывает текущую сессию. Эта функция автоматически выполняется
     * при закрытии сессии или явно через session_write_close().
     * @return bool
     */
    public function close()
    {
        echo __METHOD__;
        if ( $this->db->inTransaction() ) {
            $this->db->commit();
        } else if ( $this->releaseStatements ) {
            foreach ( $this->releaseStatements as $stmt ) {
                $stmt->execute();
            }
        }
        return true;
    }
    
    /**
     * Считывает сериализованные данные из используемого хранилища и возвращает их
     * @param string $session_id
     * @return string
     */
    public function read($session_id)
    {
        try {
            if ( $this->useTransaction ) {
                $this->db->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
                $this->db->beginTransaction();
            } else {
                $this->releaseStatements[] = $this->getLock($session_id);
            }
            
            $sql = "SELECT $this->expiryColumn, $this->dataColumn FROM $this->sessTable WHERE $this->sidColumn = :sid";
            if ( $this->useTransaction ) {
                $sql .= ' FOR UPDATE';
            }
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':sid', $session_id);
            $stmt->execute();
        } catch (\PDOException $e) {
            if ( $this->db->inTransaction() ) {
                $this->db->rollBack();
            }
            throw $e;
        }
    
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ( $result ) {
            if ( $result[$this->expiryColumn] < time() ) {
                return '';
            }
            return $result[$this->dataColumn];
        }
    
        return '';
        
    }
    
    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        $sql = "INSERT INTO $this->sessTable ($this->sidColumn, $this->expiryColumn, $this->dataColumn) " .
            "VALUES (:sid, :expiry, :data) " .
            "ON DUPLICATE KEY " .
            "UPDATE $this->dataColumn = :data, $this->expiryColumn = :expiry";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':sid', $session_id);
            $stmt->bindValue(':expiry', time());
            $stmt->bindValue(':data', $session_data);
            $stmt->execute();
        } catch (\PDOException $e) {
            if ( $this->db->inTransaction() ) {
                $this->db->rollBack();
                throw $e;
            }
        }
        return true;
    }
    
    /**
     * Уничтожение сессии при вызове session_destroy(), session_regenerate_id(true), и т.п.
     * @param string $session_id
     * @return bool
     */
    public function destroy($session_id)
    {
        $sql = "DELETE FROM $this->sessTable WHERE $this->sidColumn = :sid";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':sid', $session_id);
            $stmt->execute();
        } catch (\PDOException $e) {
            if ( $this->db->inTransaction() ) {
                $this->db->rollBack();
            }
            error_log($e->getMessage() . "\n" . $e->getTraceAsString());
            return false;
        }
    
        return true;
    }
    
    /**
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $expirationTime = time() + $maxlifetime;
        $sql = "DELETE FROM $this->sessTable WHERE $this->expiryColumn < :time";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':time', $expirationTime);
            $stmt->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage() . "\n" . $e->getTraceAsString());
            return false;
        }
    
        return true;
    }
    
    /**
     * Вызывается после метода open при session.use_strict_mode = 1
     * Возвращает true если sid проходит проверку, иначе false
     * Если возвращает false, то будет создан новый идентификатор сессии
     * @param string $session_id
     * @return bool
     */
    public function validateId($session_id)
    {
        $sql = "SELECT $this->sidColumn FROM $this->sessTable WHERE $this->sidColumn = :sid";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':sid', $session_id);
            $stmt->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e;
        }
        
        if ( $result = $stmt->fetch(\PDO::FETCH_ASSOC) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Никогда не вызывается. Документации не имеет. (PHP 7.1)
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    public function updateTimestamp($session_id, $session_data)
    {
        return true;
    }
    
    private function getLock($session_id)
    {
        $stmt = $this->db->prepare('SELECT GET_LOCK(:key, 10)');
        $stmt->bindValue(':key', $session_id);
        $stmt->execute();
        
        $releaseStmt = $this->db->prepare('SELECT RELEASE_LOCK(:key)');
        $releaseStmt->bindValue(':key', $session_id);
        return $releaseStmt;
    }
}