<?php

namespace MyGallery\Controllers;

use MyGallery\Core\Controller;


class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('AccountModel');
    }
    
    public function loginAction()
    {
        echo 'Страница логина ' . __CLASS__;
    }
    
    public function registerAction()
    {
        echo 'Страница регистрации ' . __CLASS__;
    }
}