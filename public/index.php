<?php

error_reporting(0);
session_start();

define('__DEBUG__', true );

define('__ROOT__', realpath(dirname(__FILE__) . '/..'));
define('__PUBDIR__',  __ROOT__    . '/public' );
define('__APPDIR__',  __ROOT__    . '/app' );
define('__STORAGE__', __ROOT__    . '/storage' );
define('__UPLOADS__', __STORAGE__ . '/uploads');
define('__NOIMAGE__', __APPDIR__ . '/view/images/noimage');

if( __DEBUG__ ) error_reporting(E_ALL);

require_once __ROOT__   . '/vendor/autoload.php';
require_once __APPDIR__ . '/assets/helpers.php';
require_once __APPDIR__ . '/assets/db_config.php';
require_once __APPDIR__ . '/routes/init.php';

session_write_close();
