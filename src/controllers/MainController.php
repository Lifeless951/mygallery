<?php

namespace MyGallery\Controllers;

use MyGallery\Core\Controller;
use MyGallery\Core\View;
use MyGallery\Exceptions\ViewException;

class MainController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('MainModel');
    }
    
    public function indexAction()
    {
        $pageName = 'index';
        $pageData = $this->model->getPageData() ?? [];
        try {
            $this->view->generatePage($pageName, $pageData);
        } catch (ViewException $e) {
            error_log($e);
            View::errorPage(404);
        }
    }
}