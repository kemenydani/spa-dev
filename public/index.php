<?php
ini_set('file_uploads', 'On');
ini_set('post_max_size', '100M');
ini_set('upload_max_filesize', '100M');
ini_set('memory_limit', '200M');
ini_set('max_file_uploads', '100');
ini_set('max_input_time', '120');

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
