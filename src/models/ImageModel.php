<?php

namespace MyGallery\Models;

use MyGallery\Core\Model;

class ImageModel extends Model
{
    public function __construct()
    {
        echo 'Модель ' . __CLASS__ . 'загруженна';
    }
}