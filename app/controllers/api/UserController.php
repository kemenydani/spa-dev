<?php

namespace controllers\api;

use core\DB as DB;
use Slim\Http\Request;
use Slim\Http\Response;
use core\Auth as Auth;
use models\User;

class UserController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new User() );
    }

    // TODO: to auth controller
    public function getAuth( Request $request, Response $response )
    {
        $User = Auth::user();
        
        if( !$User )
	        return $response->withStatus(401, 'Authorization failed.');
	
	    return $response->withJson( $User->getProperties() );
    }

    public function postAuth( Request $request, Response $response )
    {
        $username = $request->getParsedBody()['user'];
        $password = $request->getParsedBody()['password'];
	    $remember = $request->getParsedBody()['remember'];
	    
        $remember = isset($remember) ? true : false;

        $User = User::find( $username, 'username' );

	    if( !$User->getId() )
	    	return $response->withStatus(404, 'Could not find user.');
	    
	    if( !$User->login( $password, $remember ) )
	    	return $response->withStatus(404, 'Authorization failed.');
	    
	    return $response->withJson( $User->getProperties() );
    }
	
	public function postLogout( Request $request, Response $response )
	{
		$User = Auth::user();
		if( $User !== false ) {
			$User->logout();
			return true;
		}
		return $response->withStatus(404, 'User logout failed. Could not find logged in user.');
	}

}