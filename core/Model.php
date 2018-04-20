<?php

abstract class Model
{
    static protected $db;
    
    public function __construct()
    {
        try {
            self::$db = new PDO(DSN, USERNAME, PASSWORD);
        } catch (PDOException $ex) {
            die('Не удалось подключиться к БД');
        }
        $this->instance->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    abstract function getPageData();
}