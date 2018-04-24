<?php

namespace MyGallery\Core;

use MyGallery\Exceptions\ViewException;

class View
{
    private $twig;
    
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'] . '/../src/views');
        $this->twig = new \Twig_Environment($loader);
    }
    
    public function generatePage(string $pageName, array $data = [], string $layoutName = 'default_layout')
    {
        $data['pageContent'] = $pageName . '.twig';
        try {
            $template = $this->twig->load($layoutName . '.twig');
            $template->display($data);
        } catch (\Twig_Error_Syntax $e) {
            throw new ViewException("Ошибка в синтаксисе шаблона $pageName.twig", 1000, $e);
        } catch (\Twig_Error_Loader $e) {
            throw new ViewException('Шаблон $pageName.twig не существует в ' . TEMPLATES_PATH, 1001, $e);
        } catch (\Exception $e) {
            throw new ViewException('Не удалось создать шаблон $pageName.twig', 1002, $e);
        }
    }
    
    public static function errorPage($code)
    {
        $location = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/$code";
        header("Location: $location", true, $code);
        die();
    }
}