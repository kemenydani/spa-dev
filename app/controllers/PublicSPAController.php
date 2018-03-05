<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class PublicSPAController extends Controller
{
    public function index ( Request $request, Response $response )
    {
        echo file_get_contents('../public/spa-public/index.html');
    }
    public function build ( Request $request, Response $response )
    {
        return $response->withJson(['foo' => 'bar'])->withStatus(200);;
    }
}