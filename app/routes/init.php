<?php

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

require 'home.php';
require 'api.php';
require 'upload.php';

var_dump($app);
$app->run();
/*
try {
    $app->run();
} catch( \Exception $e) {
    echo "Unable to init slim - " . $e;
}
*/