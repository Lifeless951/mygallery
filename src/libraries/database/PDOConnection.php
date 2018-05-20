<?php

namespace MyGallery\Libraries\Database;


class PDOConnection implements IDBConnection
{
    private $db;
    
    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public function get(string $sql, array $params = [], int $fetchMode = NULL)
    {
        try {
            $stmt = $this->preparePDOStatement($sql, $params);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception('Не удалось выполнить запрос', 1001, $e);
        }
        
        return $stmt->fetchAll($fetchMode);
    }
    
    public function set(string $sql, array $params = [])
    {
        try {
            $stmt = $this->preparePDOStatement($sql, $params);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception('Не удалось выполнить запрос', 1001, $e);
        }
    }
    
    private function preparePDOStatement(string $sql, array $params = [])
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        return $stmt;
    }
}