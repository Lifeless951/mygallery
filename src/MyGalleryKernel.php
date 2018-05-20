<?php

namespace MyGallery;

use MyGallery\Core\Router;
use MyGallery\Exceptions\MyGalleryException;


/**
 * Класс инициализирующий компоненты MVC
 * MyGalleryKernel::init() должен быть вызван первым
 * Class MyGalleryKernel
 * @package MyGallery
 */
class MyGalleryKernel
{
    /**
     * @var MyGalleryConfig
     */
    private static $config;
    /**
     * @var \SessionHandlerInterface
     */
    private static $sessionHandler;
    private static $db;
    private static $isInitialized = false;
    
    public static function init(MyGalleryConfig $config, $db, \SessionHandlerInterface $sessionHandler = NULL)
    {
        self::$isInitialized = true;
        self::$config = $config;
        self::$db = $db;
        self::$sessionHandler = $sessionHandler;
    }
    
    /**
     * @throws \Exception
     */
    public static function start()
    {
        if ( !self::$isInitialized ) {
            throw new \Exception('Класс не был инициализирован');
        }
        
        self::setSessionSaveHandler();
        
        try {
            $router = new Router(self::$config->getRoutes());
            $mvcNames = $router->run();
            self::startMVC($mvcNames);
        } catch (MyGalleryException $e) {
            error_log($e);
            self::showPage404();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }
    
    public static function showPage404()
    {
        header('Location: /404', true, 404);
        die();
    }
    
    private static function setSessionSaveHandler()
    {
        $handler = self::$sessionHandler;
        if ( isset($handler) ) {
            session_set_save_handler($handler);
        }
    }
    
    /**
     * @param array $mvcNames Имена компонентов mvc
     * @throws \Exception
     */
    private static function startMVC(array $mvcNames)
    {
        extract($mvcNames);
    
        if ( class_exists($modelName) ) {
            $model = new $modelName(self::$db);
        }
        
        if ( class_exists($viewName) ) {
            $view = new $viewName();
        } else {
            throw new \Exception('View class doesn\'t exist');
        }
    
        if ( class_exists($controllerName) ) {
            $controller = new $controllerName($view, $model);
        } else {
            throw new \Exception('Controller class doesn\'t exist');
        }
    
        $controller->$actionName(...$params);
    }
}