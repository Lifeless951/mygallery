<?php

class ControllerMain extends Controller
{
    public function __constructor(View $newView, Model $newModel = NULL)
    {
        parent::__constructor($newView, $newModel);
    }
    
    public function actionIndex()
    {
        $pageName = 'index.twig';
        $data = $this->model ? $this->model->getPageData() : [];
    
        $this->view->generatePage($pageName, $data);
    }
    
    public function performAction()
    {
        $data = isset($this->model) ? $this->model->getPageData() : [];
        switch ($this->action) {
            default: {
                $this->view->generatePage('index', $data);
            }
        }
    }
}