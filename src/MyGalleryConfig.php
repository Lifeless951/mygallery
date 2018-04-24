<?php

namespace MyGallery;


class MyGalleryConfig
{
    private $projectPaths;
    private $dbInfo;
    private $routes;
    
    public function __construct(/*TODO: создать аргументы для конструктора*/)
    {
        $config = include $_SERVER['DOCUMENT_ROOT'] . '/../config/config.php';
        $routes = include $_SERVER['DOCUMENT_ROOT'] . '/../config/routes.php';
        
        $this->projectPaths = $config['PROJECT_PATHS'];
        $this->dbInfo = $config['DB_CONFIG'];
        $this->routes = $routes;
    }
    
    /**
     * @return array
     */
    public function getProjectPaths()
    {
        return $this->projectPaths;
    }
    
    /**
     * @return array
     */
    public function getDbInfo()
    {
        return $this->dbInfo;
    }
    
    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    
    
}