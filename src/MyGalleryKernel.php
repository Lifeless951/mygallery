<?php

namespace MyGallery;

use MyGallery\Core\Router;
use MyGallery\Exceptions\MyGalleryException;


class MyGalleryKernel
{
    static $config;
    static $router;
    
    public static function init(MyGalleryConfig $config)
    {
        self::$config = $config;
        self::$router = new Router($config->getRoutes());
    }
    
    public static function start()
    {
        if ( !isset(self::$router) ) {
            throw new \Exception('Класс не был инициализирован');
        }
        
        try {
            self::$router->run();
        } catch (MyGalleryException $ex) {
            error_log($ex);
            self::showPage404();
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
            self::showPage404();
        }
    }
    
    public static function showPage404()
    {
        header('Location: /404', true, 404);
        die();
    }
}