<?php

$loader = new Twig_Loader_Filesystem(__APPDIR__ . '/view/templates/');

$cacheDir = __DEBUG__ ? false : __ROOT__ . '/storage/cache/';

$twig = new Twig_Environment($loader, [
    'cache' => $cacheDir,
]);

if( __DEBUG__ ) $twig->enableDebug();