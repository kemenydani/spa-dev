<?php
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');
error_reporting(0);
session_start();

define('__DEBUG__', true );

define('__ROOT__', realpath(dirname(__FILE__) . '/..'));
define('__PUBDIR__',  __ROOT__    . '/public' );
define('__STATIC__',  __PUBDIR__  . '/static' );
define('__APPDIR__',  __ROOT__    . '/app' );
define('__STORAGE__', __ROOT__    . '/storage' );
define('__UPLOADS__', __STORAGE__ . '/uploads');
define('__NOIMAGE__', __APPDIR__ . '/view/images/noimage');

define('__HOST__', $_SERVER['HTTP_HOST']);

if( __DEBUG__ ) error_reporting(E_ALL);

require_once __ROOT__   . '/vendor/autoload.php';
require_once __APPDIR__ . '/assets/helpers.php';
require_once __APPDIR__ . '/assets/db_config.php';
require_once __APPDIR__ . '/core/init.php';

//session_write_close();
