<?php

namespace controllers\api;

use models\EnemyTeam;
use models\Squad;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Match as Match;

class MatchController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Match() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], [ 'enemy_team_id' => EnemyTeam::class, 'squad_id' => Squad::class ] );
    }

}