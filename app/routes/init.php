<?php

$configuration = [
    'settings' => [
	    'determineRouteBeforeAppMiddleware' => true,
	    'displayErrorDetails' => true,
	    'addContentLengthHeader' => false,
    ],
];

$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

require 'home.php';
require 'api.php';
require 'upload.php';


try {
    $app->run();
} catch( \Exception $e) {
    echo "Unable to init slim - " . $e;
}
