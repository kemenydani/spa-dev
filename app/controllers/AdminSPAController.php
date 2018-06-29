<?php

namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class AdminSPAController extends Controller
{
    public function index ( Request $request, Response $response )
    {
        echo file_get_contents('../public/spa-admin/index.html');
    }
}