<?php

namespace controllers;

use core\Config;
use Slim\Http\Response;
use Slim\Http\Request;
use core\DB as DB;

class AboutController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        // TODO:: db query page settings and get about

        $this->view->render($response, 'route.view.about.html.twig', ['text' => Config::instance()->get('page_about_long', '')]);
    }
}