<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController extends Controller
{
    public function index ( Request $request, Response $response )
    {
       // echo file_get_contents('../public/templates/api-bench.html');
    }
}