<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Video as Video;

class VideoController extends ModelController
{

    public function __construct()
    {
        parent::__construct( new Video() );
    }

}