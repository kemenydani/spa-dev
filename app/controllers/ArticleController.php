<?php

namespace controllers;

class ArticleController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.article.html.twig');
    }
}