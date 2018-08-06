<?php

namespace controllers;

use models\Award;
use models\AwardCollection;
use models\EventCollection;
use models\Match;
use models\MatchCollection;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Squad as Squad;
use models\SquadCollection as SquadCollection;

class SquadController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.squad.list.html.twig', ['squads' => new SquadCollection(Squad::getAll())]);
    }
    
    public function getViewSquad( Request $request, Response $response, $args )
    {
        $squad = Squad::find($args['name'], 'name');

        $q = 'SELECT * FROM `_xyz_match` WHERE squad_id = ? ORDER BY date_played DESC LIMIT 5';
        $matchCollection = MatchCollection::queryToCollection($q, $squad->getId());

        $q2 = 'SELECT * FROM _xyz_event ev LEFT JOIN _xyz_event_squad_pivot esp ON esp.event_id = ev.id WHERE esp.squad_id = ? ORDER BY id DESC LIMIT 5';
        $eventCollection = EventCollection::queryToCollection($q2, $squad->getId());

	    $this->view->render($response, 'route.view.squad.view.html.twig', [
	        'squad' => $squad,
            'matches' => $matchCollection,
            'awards' => $eventCollection,
        ]);
    }
}