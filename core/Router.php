<?php

class Router
{
    private static $controller;
    
    static function start()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        
        $controllerName = $routes[1] ? ('Controller' . ucfirst($routes[1])) : 'ControllerMain';
        $modelName = $routes[1] ? ('Model' . ucfirst($routes[1])) : NULL;
        $action = $routes[2] ? ('action' . ucfirst($routes[2])) : 'actionIndex';
        
        self::initController($controllerName, $modelName);
        if ( method_exists(self::$controller, $action) ) {
            self::$controller->$action;
        } else {
            self::showPage404();
        }
    }
    
    private static function initController($controllerName, $modelName)
    {
        $controllerAutoloadFunc = function ($className) {
            $path = '../controllers/' . $className . '.php';
            include $path; //Если не удасться подключить файл, то include кидает предупреждение
        };
    
        $modelAutoloadFunc = function ($className) {
            $path = '../controllers/' . $className . '.php';
            include $path;
        };
    
        spl_autoload_register($controllerAutoloadFunc, true, true);
        spl_autoload_register($modelAutoloadFunc, false, true); //модель может отсутств.
    
    
        try {
            $model = isset($modelName) ? new $modelName : NULL;
            $view = new View();
            self::$controller = new $controllerName($view, $model);
        } catch (LogicException $ex) {
            self::showPage404();
        }
        
        spl_autoload_unregister($controllerAutoloadFunc);
        spl_autoload_unregister($modelAutoloadFunc);
    }
    
    private static function showPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('Location:' . $host . '404', false, 404);
    }
}