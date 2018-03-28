<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\User as User;
use core\Auth as Auth;

class AuthController extends ViewController
{

    public function auth ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.user.auth.html.twig');
    }

    public function postLogin ( Request $request, Response $response )
    {
        $email = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];
        //$remember = $request->getParsedBody()['remember'];

        $remember = false;

        //$remember = $remember !== null ? true : false;

        $User = User::find( $email, 'email' );

        if( !$User->getProperty('id') )
            return $response->withStatus(404, 'Could not find user.');

        if( !$User->login( $password, $remember ) )
            return $response->withStatus(404, 'Authorization failed.');

        return $response->withJson( $User->getProperties() );
    }

    public function postRegister ( Request $request, Response $response )
    {

    }

    public function getLogout(){
        Auth::user()->logout();
    }
}
