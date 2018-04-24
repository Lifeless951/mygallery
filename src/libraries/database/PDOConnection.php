<?php

namespace MyGallery\Components\Database;


class PDOConnection implements IDBConnection
{
    private $db;
    
    public function __construct(array $DBConnectionData)
    {
        $this->connect($DBConnectionData);
    }
    
    public function connect(array $DBConnectionData)
    {
        try {
            $this->db = new \PDO($DBConnectionData['DSN'], $DBConnectionData['login'], $DBConnectionData['password']);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new Exception('Не удалось подключиться к БД', 1000, $e);
        }
    }
    
    public function get(string $sql, array $params, int $fetchMode = NULL)
    {
        try {
            $fetchMode = $fetchMode ? $fetchMode : \PDO::FETCH_ASSOC;
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new \Exception('Не удалось выполнить запрос', 1001, $e);
        }
        
        return $stmt->fetchAll($fetchMode);
    }
    
    public function set(string $sql, array $params)
    {
        // TODO: Implement set() method.
    }
    
    public function unsafeGet(string $sql, int $fetchMode = NULL)
    {
        try {
            $result = $this->db->query($sql)->fetchAll($fetchMode);
        } catch (\PDOException $e) {
            throw new \Exception('Не удалось выполнить запрос', 1001, $e);
            // TODO: Создать свой класс исключений БД.
        }
    }
    
    public function unsafeSet(string $sql)
    {
        // TODO: Implement unsafeSet() method.
    }
}