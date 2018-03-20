<?php

error_reporting(0);
session_start();

define('__ROOT__', realpath(dirname(__FILE__) . '/..'));
define('__PUBDIR__', __ROOT__ . '/public' );
define('__APPDIR__', __ROOT__ . '/app' );
define('__DEBUG__', true );

if( __DEBUG__ ) error_reporting(E_ALL);

require_once __ROOT__   . '/vendor/autoload.php';
require_once __APPDIR__ . '/routes/init.php';
require_once __APPDIR__ . '/assets/helpers.php';
require_once __APPDIR__ . '/assets/db_config.php';
//require_once __APPDIR__ . '/assets/twig_config.php';


try {
    //echo $twig->render('index.twig', [ 'debug' => __DEBUG__ ]);
}
catch(Exception $e) {
    if( __DEBUG__ ) {
        echo $e;
    } else {
        echo '404';
    }
}
