<?php

require '../vendor/autoload.php';

use MyGallery\MyGalleryKernel;
use MyGallery\MyGalleryConfig;
use MyGallery\Libraries\Authentication\MyPDOSessionHandler;
use MyGallery\Libraries\Authentication\MySession;


$config = new MyGalleryConfig();
$dbInfo = $config->getDbInfo();
$db = new PDO($dbInfo['dsn'], $dbInfo['login'], $dbInfo['password']);

try {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.gc_probability' , 1);
    ini_set('session.gc_divisor' , 100);
    ini_set('session.gc_maxlifetime', 1440);
    MySession::start($db);
} catch (Exception $e) {
    header("Location: $SERVER[HTTP_HOST]/account/login");
}

if ( isset($_SESSION['timestamp']) ) {
    if ( $_SESSION['timestamp'] <= time() ) {
        MySession::regenerateId();
    }
} else {
    $_SESSION['timestamp'] = strtotime('+15 minutes');
}
//Можно сохранять в сессии имя пользователя, тогда не нужно будет менять БД, а просто
//делать select запрос на пользователя с данным логином т.к. поле UNIQUE.

$handler = new MyPDOSessionHandler($db);
MyGalleryKernel::init($config, $db, $handler);
MyGalleryKernel::start();

