<?php

error_reporting(0);
session_start();

define('__PUBDIR__', __DIR__ );
define('__APPDIR__', '../app/' );
define('__DEBUG__', true );

require_once '../vendor/autoload.php';
require_once '../app/assets/helpers.php';
require_once '../app/routes/init.php';

$detect = new Mobile_Detect;

$loader = new Twig_Loader_Filesystem(__APPDIR__ . 'view/templates/');
$twig = new Twig_Environment($loader, array(
	//'cache' => __PUBDIR__ . '/storage/cache/',
));

try {
    echo $twig->render('index.twig', [ 'debug' => __DEBUG__ ]);
}
catch(Exception $e) {
    if(__DEBUG__) {
        echo $e;
    } else {
        echo '404';
    }
}

if(__DEBUG__) {
    error_reporting(-1);
    $twig->enableDebug();
    $detect = new Mobile_Detect();

    try {
        echo $twig->render('debug.twig', [] );
    } catch( Exception $e ){
        echo $e;
    }
}
