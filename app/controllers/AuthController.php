<?php

namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use models\User as User;
use core\Auth as Auth;
use core\Mail;
use core\Session;

class AuthController extends ViewController
{

    public function auth ( Request $request, Response $response )
    {
	    if(!Session::exists('token')) Session::put('token', bin2hex(random_bytes(32)));
	
	    $token = Session::get('token');
    	
        $this->view->render($response, 'route.view.user.auth2.html.twig', ['token' => $token]);
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

    public function getForgot( Request $request, Response $response )
    {
	    if(!Session::exists('token')) Session::put('token', bin2hex(random_bytes(32)));
	
	    $token = Session::get('token');
    	
	    $this->view->render($response, 'route.view.user.forgot.html.twig', ['token' => $token]);
    }
    
    public function postResetPassword( Request $request, Response $response )
    {
    	$formData = $request->getParsedBody();
    	
	    $email    = $formData['email'];
	    $password = $formData['password'];
	    $token    = $formData['token'];
	
	    if(!hash_equals(Session::get('token'), $token)) {
		    echo 'Token error';
		    die();
	    }
	    
	    $User = User::find($email, 'email');

	    $errors = [];
	    
	    if(!$User) $errors['email'] = 'Could not find user associated with this email';
	    
	    $validatePw = self::validatePassword($password);
	    if(strlen($validatePw)) $errors['password'] = $validatePw;
	    
	    /* start reset procedure */
	
	    $username = $User->getUsername();
	    $user_id = $User->getId();
	    
	    $secret = bin2hex(random_bytes(32));
	    $activationlink = __HOST__ . '/activatepwreset/&user_id=' . $user_id . '&secret=' . $secret;
	
	    $User->setProperty('password_change_secret', $secret);
	    $User->save();
	
	    $body = "
			<b>Dear ".$username."!</b>
			<br>
		    There was a password change request on our website.<br>
			If it was you, please click here to activate your new password (".$password."): <a href=".$activationlink.">Activate</a><br>
			If not, perhaps someone tried to steal your account. In this case, please inform or admin team.<br><br>
		    Best Regards,<br>
			".getConfig('organisation.name').";";
	    
	    try {
		    error_reporting(0);
		    $mail = new Mail(false);
		    $mail->setFrom(getConfig('organisation.email'), 'Avenue Esports');
		    $mail->Subject = 'Password change request activation';
		    $mail->addAddress('kemenydani93@gmail.com', $username);
		    $mail->Body = $body;
		    $mail->send();
		    
	    } catch(\Exception $e){
		    $errors['mail'] = 'Failed to send activation email. Please contact an administrator to activate it manually.';
	    }
	    
	    if(count($errors))
	    {
		    return $response->withStatus(401, 'Could not start the password reset process.')
			    ->withJson(['error' => $errors]);
	    }
	    
	    return $response->withStatus(200, 'Password reset request successful')
		                ->withJson(['message' => 'Password reset request successful']);
    }
    
    public function getActivatePasswordReset( Request $request, Response $response, $args )
    {
    	$queryParams = $request->getQueryParams();
    	
    	var_dump($queryParams);
    }
    
    public function postRegister ( Request $request, Response $response )
    {
        $email    = $request->getParsedBody()['email'];
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
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        $User->save();

        if( !$User->getProperty('id') ) return $response->withStatus(500, 'Failed to insert user to database.');
	
		$body = "
		<b>Dear ".$username."!</b>
		<br>
		Thank you for registering!
		Now you can log in with your login with your username and password.<br><br>
		Best Regards,<br>
		".getConfig('organisation.name').";";
        
        try {
	        error_reporting(0);
	        $mail = new Mail();
	        $mail->setFrom(getConfig('organisation.email'), 'Avenue Esports');
	        $mail->Subject = 'Registration completed';
	        $mail->addAddress('kemenydani93@gmail.com', 'foobar');
	        $mail->Body = $body;
	        //$mail->AltBody = $body';
	        $mail->send();
        } catch (\Exception $e){
        	// failed to send email
        }
        
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
