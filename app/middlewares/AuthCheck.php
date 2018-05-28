<?php

namespace middlewares;

use Slim\Http\Request;
use Slim\Http\Response;
use core\Auth as Auth;

class AuthCheck
{
    private $container;

    public function __construct($container) {
        $this->container = $container;

        var_dump($container);
    }


    public function __invoke(Request $request, Response $response, $next)
    {
        // $this->container has the DI
    }

    public function foo(){

    }

}
