<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use models\Partner as Partner;

class PartnerController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Partner() );
    }

}