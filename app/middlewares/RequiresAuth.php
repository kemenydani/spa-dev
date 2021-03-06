<?php

namespace middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\Auth as Auth;

class RequiresAuth
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if(Auth::user())
        {
            return $next($request, $response);
        }
        else
        {
            return $response->withRedirect('/home');
        }
    }
}