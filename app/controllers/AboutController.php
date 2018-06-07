<?php

namespace controllers;

use Slim\Http\Response;
use Slim\Http\Request;
use core\DB as DB;

class AboutController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        // TODO:: db query page settings and get about

        $text = [];

        $this->view->render($response, 'route.view.about.html.twig', ['text' => $text]);
    }
}