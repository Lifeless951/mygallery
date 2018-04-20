<?php

include SITE_ROOT . '/config.php';
require SITE_ROOT . '/composer/autoload.php';

include_once '../core/Router.php';
include_once '../core/Controller.php';
include_once '../core/Model.php';
include_once '../core/View.php';


Router::start();


