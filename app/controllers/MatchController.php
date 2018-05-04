<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\Match as Match;
use models\MatchCollection as MatchCollection;

class MatchController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $matches = new MatchCollection(Match::getAll());

        $this->view->render($response, 'route.view.match.list.html.twig', ['matches' => $matches]);
    }
    
    public function getViewMatch( Request $request, Response $response, $args )
    {
        $match = Match::find($args['name'], 'name');
	    $this->view->render($response, 'route.view.match.view.html.twig', ['match' => $match]);
    }
}