<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use models\EnemyTeam as EnemyTeam;

class EnemyController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new EnemyTeam() );
    }

}