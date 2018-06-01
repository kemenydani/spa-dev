<?php

namespace controllers;

use models\ProductCollection;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\ArticleCollection;
use models\SquadCollection;
use models\MatchCollection;
use models\EventCollection;
use models\PartnerCollection;

class HomeController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $q1 = " SELECT * FROM _xyz_article art " .
              " WHERE art.highlighted = ? "      .
              " ORDER BY art.date_created DESC " .
              " LIMIT 2 "
        ;

        $q2 = " SELECT * FROM _xyz_article art " .
              " WHERE art.highlighted = ? "      .
              " ORDER BY art.date_created DESC " .
              " LIMIT 4 "
        ;

        $hlArticles  = (ArticleCollection::queryToCollection($q1, 1))->getFormatted();
        $ltArticles  = (ArticleCollection::queryToCollection($q2, 0))->getFormatted();

        $q3 = " SELECT * FROM _xyz_squad sq " .
	          " WHERE sq.featured = ? "       .
	          " ORDER BY sq.featured DESC "
        ;
        
        $squadCollection = SquadCollection::queryToCollection($q3, 1);

        $q4 = " SELECT * FROM _xyz_match mc " .
              " ORDER BY mc.id DESC "         .
              " LIMIT 5 "
        ;

        $q5 = " SELECT * FROM _xyz_match mc " .
            " WHERE mc.featured = ? "         .
            " ORDER BY mc.id DESC "           .
            " LIMIT 3 "
        ;

        $ltMatches = MatchCollection::queryToCollection($q4);
        $topMatches = MatchCollection::queryToCollection($q5, true);

        $q6 = " SELECT * FROM _xyz_event ev " .
              " ORDER BY ev.start_date DESC " .
              " LIMIT 5 "
        ;

        $ltEvents = EventCollection::queryToCollection($q6);

        $q7 = " SELECT * FROM _xyz_partner pt " .
              " WHERE featured_top = ? "        .
              " ORDER BY pt.id ASC"             .
              " LIMIT 4 "
        ;

        $q8 = " SELECT * FROM _xyz_partner pt " .
              " WHERE featured_bottom = ? "     .
              " ORDER BY pt.id DESC "           .
              " LIMIT 4 "
        ;

        $partnersTop = PartnerCollection::queryToCollection($q7, true);
        $partnersBot = PartnerCollection::queryToCollection($q8, true);

        $q9 = " SELECT * FROM _xyz_product pr " .
              " WHERE featured = ? "            .
              " ORDER BY pr.id DESC "           .
              " LIMIT 10 "
        ;

        $featuredItems = ProductCollection::queryToCollection($q9, true);
        $randomFeaturedItem = null;
        if(is_array($featuredItems->getModels()))
        {
            $fiModels = $featuredItems->getModels();

            $randKey = array_rand($fiModels, 1);
            $randomFeaturedItem = $fiModels[$randKey];
        }

        $this->view->render($response, 'route.view.home.html.twig', [
            'articles' => [
                'hl' => $hlArticles,
                'lt' => $ltArticles,
            ],
            'featuredItem' => $randomFeaturedItem,
            'ltEvents' => $ltEvents,
            'topMatches' => $topMatches,
            'matches' => $ltMatches,
	        'squads' => $squadCollection,
            'partnersTop' => $partnersTop,
            'partnersBot' => $partnersBot,
        ]);
    }
}