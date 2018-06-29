<?php

namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class MediaController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.media.list.html.twig', []);
    }
}