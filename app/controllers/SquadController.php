<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\Squad as Squad;
use models\SquadCollection as SquadCollection;

class SquadController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.squad.list.html.twig', ['squads' => new SquadCollection(Squad::getAll())]);
    }
    
    public function getViewSquad( Request $request, Response $response )
    {
	    $this->view->render($response, 'route.view.squad.view.html.twig');
    }
}