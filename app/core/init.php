<?php

use core\Auth as Auth;
use core\Config;
use models\MatchCollection;
use models\PartnerCollection;
use models\SquadCollection;
use models\SquadMemberCollection;

// Slim configuration
$configuration = [
    'settings' => [
	    'determineRouteBeforeAppMiddleware' => true,
	    'displayErrorDetails' => true,
	    'addContentLengthHeader' => false,
    ],
    'view' => function( $container ) {

        $cacheDir = __DEBUG__ ? '' : __ROOT__ . '/storage/cache/';

        //TODO:: enable caching
        $settings = [
           // 'cache' => $cacheDir
        ];

        $view = new \Slim\Views\Twig( __APPDIR__ . '/view/templates', $settings );

        $view->getEnvironment()->addGlobal('isLogged', Auth::isLoggedIn());
        $view->getEnvironment()->addGlobal('AuthUser', Auth::user());
        $view->getEnvironment()->addGlobal('Config',  Config::instance());

        $q10 = "SELECT * FROM _xyz_squad_member";
        $squadMemberCollection  = (SquadMemberCollection::queryToCollection($q10));

        $mixedSquadMembers = $squadMemberCollection->getModels();

        if(is_array($mixedSquadMembers))
        {
            shuffle($mixedSquadMembers);
            $mixedSquadMembers = array_slice($mixedSquadMembers, 0, 5);
        }
        else
        {
            $mixedSquadMembers = [];
        }

        $view->getEnvironment()->addGlobal('mixedSquadMembers',$mixedSquadMembers);


        $q3 = " SELECT * FROM _xyz_squad sq " .
            " WHERE sq.featured = ? "       .
            " ORDER BY sq.featured DESC "
        ;

        $squadCollection = SquadCollection::queryToCollection($q3, 1);

        $allMembers = [];
        $trendingMember = null;

        /* @var \models\Squad $Squad */
        foreach($squadCollection->getModels() as $Squad)
        {
            $members = $Squad->getMembers();
            foreach($members as $Member) $allMembers[] = $Member;
        }

        if(count($allMembers))
        {
            $randMemberKey = array_rand($allMembers, 1);
            $trendingMember = $allMembers[$randMemberKey];
        }

        $view->getEnvironment()->addGlobal('trendingMember',  $trendingMember);

        $q4 = " SELECT * FROM _xyz_match mc " .
            " ORDER BY mc.id DESC "         .
            " LIMIT 4 "
        ;

        $ltMatches = MatchCollection::queryToCollection($q4);

        $view->getEnvironment()->addGlobal('matches',  $ltMatches);

        $q8 = " SELECT * FROM _xyz_partner pt " .
            " WHERE featured_bottom = ? "     .
            " ORDER BY pt.id DESC "           .
            " LIMIT 4 "
        ;

        $partnersBot = PartnerCollection::queryToCollection($q8, true);

        $view->getEnvironment()->addGlobal('partnersBot', $partnersBot);


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
