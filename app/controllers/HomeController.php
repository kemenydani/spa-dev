<?php

namespace controllers;

use models\MatchMap;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use core\DB as DB;

use models\Article as Article;
use models\Match as Match;

use models\ArticleCollection;
use models\SquadCollection;
use models\MatchCollection;
use models\EventCollection;

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
            " LIMIT 1 "
        ;

        $ltMatches = MatchCollection::queryToCollection($q4);
        $topMatches = MatchCollection::queryToCollection($q5, true);

        $q6 = " SELECT * FROM _xyz_event ev " .
              " ORDER BY ev.start_date DESC " .
              " LIMIT 5 "
        ;

        $ltEvents = EventCollection::queryToCollection($q6);

        $this->view->render($response, 'route.view.home.html.twig', [
            'articles' => [
                'hl' => $hlArticles,
                'lt' => $ltArticles,
            ],
            'ltEvents' => $ltEvents,
            'topMatches' => $topMatches,
            'matches' => $ltMatches,
	        'squads' => $squadCollection
        ]);
    }
}