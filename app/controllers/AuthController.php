<?php

namespace controllers;

use core\Config;
use models\UserProfile;
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

        $User = null;

        $errors = [];

        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $User = User::find($email, 'email');
        } else {
            $errors['email'] = 'Invalid email format.';
        }

        if(!$User || !$User->getId())
            return $response->withStatus(404, 'Wrong email or password.')->withJson(['success' => false, 'errors' => $errors]);

        if(!$User->login( $password, $remember))
        {
            $errors['email'] = 'Wrong email or password given.';
            $errors['password'] = 'Wrong email or password given.';
            return $response->withJson(['success' => false, 'errors' => $errors])->withStatus(404, 'Wrong email or password.');
        }

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
    	
	    $email      = $formData['email'];
	    $password   = $formData['password'];
        $password_r = $formData['password_repeat'];
	    $token      = $formData['token'];
	
	    if(!hash_equals(Session::get('token'), $token)) die();

	    $errors = [];

	    $User = null;

        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $User = User::find($email, 'email');
            if(!$User) $errors['email'] = 'Could not find user associated with this email.';
        }
        else
        {
            $errors['email'] = 'Invalid email format';
        }

        if($password !== $password_r) $errors['password'] = 'Passwords must match.';

	    $validatePw = self::validatePassword($password);
	    if(strlen($validatePw)) $errors['password'] = $validatePw;

	    if($User)
	    {
	        if(strlen($User->getPasswordChangeSecret() > 0)) $errors['email'] = 'You already have a pending password reset request. Check your email!';
        }

	    if(count($errors))
	    {
		    return $response->withStatus(401, 'Could not start the password reset process.')
			    ->withJson(['error' => $errors]);
	    }

        /* start reset procedure */

        $username = $User->getUsername();
        $user_id = $User->getId();

        try
        {
            $secret = bin2hex(random_bytes(32));
        }
        catch(\Exception $e)
        {
            $secret = base64_encode(md5($email . date('Y-m-d H:i:s')));
        }

        $activationLink = __HOST__ . '/activatepwreset/?uid=' . $user_id . '&secret=' . $secret;

        $User->setProperty('password_change_secret', $secret);
        $User->setProperty('password_temporary', password_hash($password, PASSWORD_BCRYPT));
        $User->save();

        $body = "
			<b>Dear ".$username."!</b>
			<br>
		    There was a password change request on our website.<br>
			If it was you, please click <a href=".$activationLink.">here to activate</a> your new password (".$password.").<br>
			If not, perhaps someone tried to steal your account. In this case, please inform or admin team.<br><br>
		    Best Regards,<br>
			".getConfig('organisation.name').";";

        try {
            error_reporting(0);
            $mail = new Mail();
            $mail->setFrom(getConfig('organisation.email'), 'Avenue Esports');
            $mail->Subject = 'Password change request activation';
            // TODO: change this to real email
            $mail->addAddress($User->getEmail(), $username);
            $mail->Body = $body;
            $mail->send();

        }
        catch(\Exception $e)
        {
            $errors['email'] = 'Failed to send activation email. Please contact an administrator to activate your request manually.';
        }

	    return $response->withStatus(200, 'Password reset request successful')
		                ->withJson(['message' => 'Password reset request successful']);
    }
    
    public function validatePasswordReset($queryParams, $User)
    {
    
    }
    
    public function getActivatePasswordReset( Request $request, Response $response, $args )
    {
    	$queryParams = $request->getQueryParams();

        if(!array_key_exists('uid', $queryParams) || !array_key_exists('secret', $queryParams))
        {
            echo 'Activation url is corrupted. Please contact an administrator to activate your password manually';
            die();
        }

        $errors = [];

		$user_id = $queryParams['uid'];
		$urlSecret  = $queryParams['secret'];
		
		$User = User::find($user_id, 'id');
		if(!$User) $errors[] = 'Cold not find user.';


		if(count($errors) === 0)
		{
            $storedSecret = $User->getPasswordChangeSecret();
            $tempPassword = $User->getPasswordTemporary();

            if(is_string($storedSecret) && is_string($tempPassword) && hash_equals($storedSecret, $urlSecret))
            {
                $User->setProperty('password', $User->getPasswordTemporary());
                $User->setProperty('password_temporary',     null);
                $User->setProperty('password_change_secret', null);
                $User->save();
            } else {
                $errors[] = 'Activation url is corrupted. Please contact an administrator.';
            }
        }

	    $this->view->render($response, 'route.view.user.activatepwreset.html.twig', ['errors' => $errors]);
    }

    //https://www.kaplankomputing.com/blog/tutorials/php/setting-recaptcha-2-0-ajax-demotutorial/
    public function postRegister ( Request $request, Response $response )
    {
        $email    = $request->getParsedBody()['email'];
        $username = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];
        $captcha = $request->getParsedBody()['captcha'];
        $errors = [];

        $secret = Config::instance()->get('page_recaptcha_secret_key', '');
        $chapresp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$captcha}");
        $captcha_success = json_decode($chapresp);
        if ($captcha_success->success == false) {
            $errors['captcha'] = 'Are you a robot?';
        }

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

        $UserProfile = UserProfile::create([
            'user_id' => $User->getProperty('id')
        ]);

        $UserProfile->save();

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
