<?php

namespace controllers;

use models\ArticleCollection;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use core\DB as DB;

class HomeController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $hlArticles   = DB::instance()->getRows('SELECT * FROM _xyz_article WHERE highlighted = 1 ORDER BY date_created DESC LIMIT 2');
        $lastArticles = DB::instance()->getRows('SELECT * FROM _xyz_article WHERE highlighted = 0 ORDER BY date_created DESC LIMIT 4');

        $hlArticles   = new ArticleCollection($hlArticles);
        $lastArticles = new ArticleCollection($lastArticles);

        $featured = [];
        $lastMatches = [];
        $topMatch = [];
        $events = [];

        $layout = [
            'headline_articles' => $hlArticles,
            'latest_articles'   => $lastArticles,
            'featured_content'  => $featured,
            'top_match'         => $topMatch,
            'latest_matches'    => $lastMatches,
            'events' => $events
        ];


        $this->view->render($response, 'route.view.home.html.twig', [
            'hlArticles' => $hlArticles,
            'lastArticles' => $lastArticles
        ]);
    }
}