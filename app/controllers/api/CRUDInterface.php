<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

interface CRUDInterface
{
    public function __construct();

    public function postCreate( Request $request, Response $response );
}