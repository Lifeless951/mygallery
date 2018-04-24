<?php

namespace MyGallery\Controllers;

use MyGallery\Core\Controller;


class ImageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('ImageModel');
    }
    
    public function showAction($id)
    {
        echo "Страница картинки id=$id: " . __CLASS__;
    }
}