<?php

namespace controllers;

use core\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminSPAController
{
    public function index ( Request $request, Response $response, $next )
    {
        echo file_get_contents('../public/spa-admin/index.html');
    }
}