<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class ArticleController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.article.list.html.twig');
    }

    public function read ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.article.read.html.twig');
    }
}