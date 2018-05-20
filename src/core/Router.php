<?php

namespace MyGallery\Core;

use MyGallery\Exceptions\RouterException;
use MyGallery\Exceptions\ControllerException;

class Router
{
    private $routePatterns;
    
    public function __construct(array $routes)
    {
        if ( $routes == false ) {
            throw new RouterException("В конструктор передан пустой массив маршрутов", 1002);
        }
        
        $this->routePatterns = $routes;
    }
    
    
    /**
     * Проверяем REQUEST_URI, на соотв. одному из шаблонов, заданных в src/config/routes.php
     * Если находим, то создаем нужный контроллер
     * Иначе показываем страницу 404
     * @throws RouterException
     * @return array
     */
    public function run()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $route = explode('/', $this->getRoute($uri));
        
        $controllerName = 'MyGallery\\Controllers\\' . ucfirst($route[0]) . 'Controller';
        $modelName = 'MyGallery\\Models\\' . ucfirst($route[0]) . 'Model';
        $viewName = 'MyGallery\\Core\\View';
        $actionName = $route[1] . 'Action';
        $params = array_slice($route, 2);
        
        return [
            'controllerName' => $controllerName,
            'modelName' => $modelName,
            'viewName' => $viewName,
            'actionName' => $actionName,
            'params' => $params
        ];
    }
    
    
    /**
     * Если в routes.php есть рег. шаблон, соотв URI, то преобразовываем URI и возвращаем строку вида:
     * controller/action(?:/params)*
     * @param $uri
     * @throws RouterException
     * @return string
     */
    private function getRoute(string $uri)
    {
        foreach ($this->routePatterns as $uriPattern => $routePattern) {
            if ( preg_match("~^$uriPattern$~", $uri) ) {
                $route = preg_replace("~^$uriPattern$~", "$routePattern", $uri);
                return $route;
            }
        }
        throw new RouterException("Адрес $uri не задан в массиве маршрутов", 1000);
    }
}