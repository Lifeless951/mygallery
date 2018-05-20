<?php

namespace MyGallery\Controllers;

use MyGallery\Core\Controller;
use MyGallery\Libraries\Authentication\MySession;


class AccountController extends Controller
{
    public function loginAction()
    {
        MySession::regenerateId();
        echo 'Страница логина ' . __CLASS__;
        
    }
    
    public function logoutAction()
    {
        MySession::regenerateId();
    }
    
    public function registerAction()
    {
        echo 'Страница регистрации ' . __CLASS__;
    }
}