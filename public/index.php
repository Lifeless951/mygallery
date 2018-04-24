<?php

require '../vendor/autoload.php';

use MyGallery\MyGalleryKernel;
use MyGallery\MyGalleryConfig;


$config = new MyGalleryConfig();
MyGalleryKernel::init($config);
MyGalleryKernel::start();

