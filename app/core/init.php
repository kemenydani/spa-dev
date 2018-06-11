<?php

use core\Auth as Auth;
use core\Config;

// Slim configuration
$configuration = [
    'settings' => [
	    'determineRouteBeforeAppMiddleware' => true,
	    'displayErrorDetails' => true,
	    'addContentLengthHeader' => false,
    ],
    'view' => function( $container ) {

        $cacheDir = __DEBUG__ ? false : __ROOT__ . '/storage/cache/';

        //TODO:: enable caching
        $settings = [
            //'cache' => $cacheDir
        ];
        
        $view = new \Slim\Views\Twig( __APPDIR__ . '/view/templates', $settings );

        $view->getEnvironment()->addGlobal('isLogged', Auth::isLoggedIn());
        $view->getEnvironment()->addGlobal('AuthUser', Auth::user());
        $view->getEnvironment()->addGlobal('Config',  Config::instance());

        $view->addExtension(new \Slim\Views\TwigExtension(
            $container->router,
            $container->request->getUri()
        ));

        return $view;
    }
];

$app = new \Slim\App( new \Slim\Container( $configuration ) );

$routes = glob(__APPDIR__ . DIRECTORY_SEPARATOR . 'routes/*.php');

foreach ($routes as $file) require_once $file;

try {
    $app->run();
    //throw new Exception();
} catch( \Exception $e) {
    echo "Unable to init slim - " . $e;
}
