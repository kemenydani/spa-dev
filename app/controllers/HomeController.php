<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use core\DB as DB;

class HomeController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $hlArticles = DB::instance()->getRows('SELECT * FROM _xyz_article WHERE highlighted = 1 ORDER BY date_created DESC LIMIT 2');
        $lastArticles = DB::instance()->getRows('SELECT * FROM _xyz_article WHERE highlighted = 0 ORDER BY date_created DESC LIMIT 4');

        $this->view->render($response, 'route.view.home.html.twig', [
            'hlArticles' => $hlArticles,
            'lastArticles' => $lastArticles
        ]);
    }
}