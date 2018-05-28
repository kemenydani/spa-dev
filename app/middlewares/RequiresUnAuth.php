<?php

namespace middlewares;

use Slim\Http\Request;
use Slim\Http\Response;
use core\Auth as Auth;

class RequiresUnAuth
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if(!Auth::user())
        {
            return $next($request, $response);
        }
        else
        {
            return $response->withRedirect('/home');
        }
    }
}