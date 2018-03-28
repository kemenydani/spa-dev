<?php

use core\Auth as Auth;
use models\User as User;

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
            //'cache' => $cacheDir
        ];

        $view = new \Slim\Views\Twig( __APPDIR__ . '/view/templates', $settings );

        $view->getEnvironment()->addGlobal('isLogged', Auth::isLoggedIn());
        $view->getEnvironment()->addGlobal('AuthUser', Auth::user());

        $view->addExtension(new \Slim\Views\TwigExtension(
            $container->router,
            $container->request->getUri()
        ));

        return $view;
    }
];

$app = new \Slim\App( new \Slim\Container( $configuration ) );

// Routes
require_once 'auth.php';
require_once 'home.php';
require_once 'article.php';
require_once 'squad.php';
require_once 'video.php';
require_once 'stream.php';
require_once 'partner.php';
require_once 'user.php';

require_once 'api.php';
require_once 'upload.php';

// Boot
try {
    $app->run();
    //throw new Exception();
} catch( \Exception $e) {
    echo "Unable to init slim - " . $e;
}
