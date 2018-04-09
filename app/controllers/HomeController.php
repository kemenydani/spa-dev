<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use core\DB as DB;

class HomeController extends ViewController
{
    public function index ( Request $request, Response $response )
    {


       $this->view->render($response, 'route.view.home.html.twig', [

       ]);
    }
}