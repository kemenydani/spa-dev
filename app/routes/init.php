<?php

// Slim configuration
$configuration = [
    'settings' => [
	    'determineRouteBeforeAppMiddleware' => true,
	    'displayErrorDetails' => true,
	    'addContentLengthHeader' => false,
    ],
    'view' => function( $container ) {

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
    }
];

$app = new \Slim\App( new \Slim\Container( $configuration ) );

// Routes
require 'home.php';
require 'article.php';
require 'squad.php';
require 'video.php';
require 'stream.php';
require 'partner.php';
require 'user.php';

require 'api.php';
require 'upload.php';

// Boot
try {
    $app->run();
    //throw new Exception();
} catch( \Exception $e) {
    echo "Unable to init slim - " . $e;
}
