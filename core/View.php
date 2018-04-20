<?php

class View
{
    public function generatePage($pageName, $data = [])
    {
        try {
            $loader = new Twig_Loader_Filesystem(TEMPLATES_PATH);
            $twig = new Twig_Environment($loader);
            // TODO: загрузка должна идти в общий шалон main, как {{ content }}
            $template = $twig->load($pageName);
            $template->render($data);
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }
}