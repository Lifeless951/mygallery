<?php

namespace MyGallery\Core;

use MyGallery\Libraries\Database;

abstract class Model
{
    protected $db;
    
    public function __construct($db)
    {
        $this->db = new Database\PDOConnection($db);
    }
}