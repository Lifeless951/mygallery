<?php

namespace MyGallery\Core;

use MyGallery\Exceptions\ControllerException;

abstract class Controller
{
    protected $model;
    protected $view;
    protected $method;
    protected $isAjax;
    
    
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->view = new View();
    }
    
    protected function loadModel(string $modelName)
    {
        $modelNamespace = 'MyGallery\\Models\\';
        $modelClass = $modelNamespace . $modelName;
        if ( !class_exists($modelClass) ) {
            $this->model = new $modelClass();
        }
    }
    
    public function __call($name, $arguments)
    {
        throw new ControllerException("Попытка вызова несуществующего экшена $name", 1000);
    }
}