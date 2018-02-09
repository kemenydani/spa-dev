<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Team as Team;

class TeamController extends ModelController
{

    public function __construct()
    {
        parent::__construct( new Team() );
    }

}