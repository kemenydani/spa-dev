<?php

namespace middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\Auth as Auth;
use core\Role as Role;

class RequiresRole
{
    private $_args_ = [];

    public function __construct($args)
    {
       $this->_args_ = $args;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $User = Auth::user();
        if($User)
        {
            // TODO:: check if user really has the permission
            if($this->_args_[0] === 'users.list'){
                return $next($request, $response);
            } else {
                return $response->withStatus(404);
            }
        }
        else
        {
            return $response->withStatus(404);
        }
    }
}