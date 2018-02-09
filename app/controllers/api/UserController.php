<?php

namespace controllers\api;

use core\Session;
use core\DB as DB;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\Auth as Auth;
use models\User as User;

class UserController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new User() );
    }

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