<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class AdminSPAController extends Controller
{
    public function index ( Request $request, Response $response )
    {
        echo file_get_contents('../public/spa-admin/index.html');
    }
}