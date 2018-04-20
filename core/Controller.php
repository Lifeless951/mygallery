<?php

abstract class Controller
{
    protected $model;
    protected $view;
    
    public function __constructor(View $newView, Model $newModel = NULL)
    {
        $this->model = $newModel;
        $this->view = $newView;
    }
}