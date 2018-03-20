<?php

namespace controllers;

class StreamController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.stream.html.twig');
    }
}