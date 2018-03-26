<?php
/*
$row = "select_result";
$comments = $row;

$comments = array(
    1 => array('id' => 1, 'pid' => 0, 'ch' => array()),
    2 => array('id' => 2, 'pid' => 0, 'ch' => array()),
    3 => array('id' => 3, 'pid' => 0, 'ch' => array()),
    5 => array('id' => 5, 'pid' => 0, 'ch' => array()),
    11 => array('id' => 11, 'pid' => 0, 'ch' => array()),
    17 => array('id' => 17, 'pid' => 0, 'ch' => array()),
    23 => array('id' => 23, 'pid' => 0, 'ch' => array()),
    28 => array('id' => 28, 'pid' => 0, 'ch' => array()),

    4 => array('id' => 4, 'pid' => 1, 'ch' => array()),
    6 => array('id' => 6, 'pid' => 1, 'ch' => array()),
    8 => array('id' => 8, 'pid' => 2, 'ch' => array()),
    9 => array('id' => 9, 'pid' => 2, 'ch' => array()),
    7 => array('id' => 7, 'pid' => 3, 'ch' => array()),
    12 => array('id' =>12, 'pid' => 7, 'ch' => array()),
    13 => array('id' => 13, 'pid' => 12, 'ch' => array()),
);

foreach ($comments as $k => &$v) {
    if ($v['pid'] != 0) {
        $comments[$v['pid']]['ch'][] =& $v;
    }
}

unset($v);

foreach ($comments as $k => $v) {
    if ($v['pid'] != 0) {
        unset($comments[$k]);
    }
}

echo '<pre>'; print_r($comments); echo '</pre>';
*/

error_reporting(0);
session_start();
session_write_close();
define('__DEBUG__', true );

define('__ROOT__', realpath(dirname(__FILE__) . '/..'));
define('__PUBDIR__',  __ROOT__    . '/public' );
define('__APPDIR__',  __ROOT__    . '/app' );
define('__STORAGE__', __ROOT__    . '/storage' );
define('__UPLOADS__', __STORAGE__ . '/uploads');


if( __DEBUG__ ) error_reporting(E_ALL);

require_once __ROOT__   . '/vendor/autoload.php';
require_once __APPDIR__ . '/assets/helpers.php';
require_once __APPDIR__ . '/assets/db_config.php';
require_once __APPDIR__ . '/routes/init.php';

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
