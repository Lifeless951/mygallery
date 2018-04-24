<?php

namespace MyGallery\Components\Database;

interface IDBConnection
{
    public function connect(array $source);
    public function get(string $sql, array $params, int $fetchMode = NULL);
    public function set(string $sql, array $params);
    public function unsafeGet(string $sql, int $fetchMode = NULL);
    public function unsafeSet(string $sql);
}