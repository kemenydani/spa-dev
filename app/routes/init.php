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

$container = $app->getContainer();

$container['view'] = function( $container )
{
    $view = new \Slim\Views\Twig(
        __APPDIR__ . '/view/templates', [
            'cache' => false
        ]
    );

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    return $view;
};

require 'home.php';
require 'api.php';
require 'upload.php';

try {
    $app->run();
} catch( \Exception $e) {
    echo "Unable to init slim - " . $e;
}
