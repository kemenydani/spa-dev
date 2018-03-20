<?php

$configuration = [
    'settings' => [
	    'determineRouteBeforeAppMiddleware' => true,
	    'displayErrorDetails' => true,
	    'addContentLengthHeader' => false,
    ],
];

$container = new \Slim\Container($configuration);
$app = new \Slim\App($container);

$container['view'] = function( $container )
{
    $cacheDir = __DEBUG__ ? false : __ROOT__ . '/storage/cache/';

    $settings = [
        'cache' => $cacheDir
    ];

    $view = new \Slim\Views\Twig( __APPDIR__ . '/view/templates', $settings );

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    return $view;
};

require 'home.php';
require 'article.php';
require 'squad.php';
require 'video.php';
require 'stream.php';
require 'partner.php';
require 'user.php';

require 'api.php';
require 'upload.php';

try {
    $app->run();
} catch( \Exception $e) {
    echo "Unable to init slim - " . $e;
}
