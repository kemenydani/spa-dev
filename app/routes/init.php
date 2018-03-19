<?php

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
	    'mode' => 'development'
    ],
];

$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

require 'home.php';
require 'api.php';
require 'upload.php';


$app->run();
