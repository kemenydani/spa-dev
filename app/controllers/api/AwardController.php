<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use models\Award;

class AwardController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Award() );
    }

}