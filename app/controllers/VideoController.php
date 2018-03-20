<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class VideoController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.video.html.twig');
    }
}