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
        $this->view->render($response, 'route.view.user.auth2.html.twig');
    }

    public function checkUnique( Request $request, Response $response )
    {
        $field = $request->getParsedBody()['field'];
        $value = $request->getParsedBody()['value'];

        $exists = User::find($value, $field);

        return $response->withJson( is_object($exists) );
    }

    public function postLogin ( Request $request, Response $response )
    {
        $email    = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];
        $remember = $request->getParsedBody()['remember'];

        $User = User::find($email, 'email');

        if(!$User->getId())
            return $response->withStatus(404, 'Could not find user.');

        if(!$User->login( $password, $remember))
            return $response->withStatus(404, 'Authorization failed.');

        return $response->withJson($User->getProperties());
    }

    public function postRegister ( Request $request, Response $response )
    {
        $email = $request->getParsedBody()['email'];
        $username = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];

        $errors = [];

        if(User::find($username, 'username')) $errors['username'] = 'Username already exists';
        if(User::find($email, 'email')) $errors['email'] = 'Email already exists';

        if(count($errors) > 0) return $response->withJson(['success' => false, 'details' => $errors ]);

        $User = User::create([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);

        $User->save();

        if( !$User->getProperty('id') ) return $response->withStatus(500, 'Failed to insert user to database.');

        return $response->withJson([
            'success' => true,
            'details' => [
                'username' => $username,
                'email' => $email
            ]
        ])->withStatus(200);
    }

    public function getLogout( Request $request, Response $response ){
        Auth::user()->logout();
        return $response->withRedirect('/auth');
    }
}
