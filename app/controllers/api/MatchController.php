<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use models\Match as Match;

class MatchController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Match() );
    }

}