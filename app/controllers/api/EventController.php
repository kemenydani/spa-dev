<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use models\Event as Event;

class EventController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Event() );
    }

}