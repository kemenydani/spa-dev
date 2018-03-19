<?php

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
	    'mode' => 'production'
    ],
];

$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);
$app->config('debug', true);
require 'home.php';
require 'api.php';
require 'upload.php';
echo 1;
die();
try {
    $app->run();
} catch( Exception $e) {
    echo "Unable to init slim - " . $e;
}