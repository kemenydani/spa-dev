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
            return $response->withJson(['success' => false])->withStatus(404, 'Authorization failed.');

        return $response->withJson(['success' => true])->withStatus(200);
    }


    public static function validateUsername( $username )
    {
        if(strlen($username) === 0) return 'This field is required!';
        if(strlen($username) <   3) return 'The username must be minimum 3 characters long!';
        if(is_numeric($username))   return 'The username must contain letters.';
        if(User::find($username, 'username')) return 'The username: '.$username.' already exists';
        return null;
    }

    public static function validateEmail( $email )
    {
        if(strlen($email) === 0) return 'This field is required!';
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return 'The format of the email address is not valid!';
        if(User::find($email, 'email')) return 'The email: '.$email.' already exists';
        return null;
    }

    public static function validatePassword( $password )
    {
        if(strlen($password) === 0) return 'This field is required!';
        if(strlen($password) <   6) return 'The password must be minimum 6 characters long!';
        $format = 'The password must contain at least ';
        $msgs = [];
        if(!preg_match('/[A-Z]/', $password)) $msgs[] = 'one uppercase letter';
        if(!preg_match('/[0-9]/', $password)) $msgs[] = 'one number';
        if(count($msgs) > 0) return $format . implode(' and ', $msgs);
        return null;
    }

    public function postRegister ( Request $request, Response $response )
    {
        $email = $request->getParsedBody()['email'];
        $username = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];

        $errors = [];

        $usernameErr = self::validateUsername($username);
        $emailErr    = self::validateEmail($email);
        $passwordErr = self::validatePassword($password);

        if($usernameErr) $errors['username'] =  $usernameErr;
        if($emailErr)    $errors['email']    =  $emailErr;
        if($passwordErr) $errors['password'] =  $passwordErr;

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
