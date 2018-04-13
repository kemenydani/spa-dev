<?php

namespace controllers;

use models\ArticleCollection;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use core\DB as DB;
use models\Article as Article;

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
              " LIMIT 6 "
        ;

        $hlArticles  = (ArticleCollection::queryToCollection($q1, 1))->getFormatted();
        $ltArticles  = (ArticleCollection::queryToCollection($q1, 0))->getFormatted();

        $this->view->render($response, 'route.view.home.html.twig', [
            'articles' => [
                'highlighted' => $hlArticles,
                'last'        => $ltArticles,
            ],
            'events' => [

            ]
        ]);
    }
}