<?php

namespace MyGallery\Libraries\Database;

interface IDBConnection
{
    public function get(string $sql, array $params = [], int $fetchMode = NULL);
    public function set(string $sql, array $params = []);
}