<?php

namespace controllers\api;

use core\DB as DB;
use Intervention\Image\ImageManager;
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

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items){

            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$user)
            {
                $path = User::IMAGE_PATH . DIRECTORY_SEPARATOR . $user['profile_picture'];

                $user['imageDataUrl'] = null;

                if(!isReadableFile($path)) continue;

                $img = $ImageManager->make($path);
                $img->encode('data-url');
                $user['imageDataUrl'] = $img->getEncoded();
            }

            return $items;
        });
    }

    // TODO: to auth controller
    public function getAuth( Request $request, Response $response )
    {
        /* @var User $User */
        $User = Auth::user();
        
        if( !$User ) return $response->withStatus(401, 'Authorization failed.');
	    if( !$User->isAdminUser() ) return $response->withStatus(401, 'Authorization failed: no admin rights.');

	    return $response->withJson( $User->getProperties() );
    }

    public function postAuth( Request $request, Response $response )
    {
        $email = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];
	    $remember = $request->getParsedBody()['remember'];
	    
        $remember = isset($remember) ? true : false;

        $User = User::find( $email, 'email' );

	    if( !$User->getId() )
	    	return $response->withStatus(404, 'Authorization failed: could not find user.');
	    
	    if( !$User->login( $password, $remember ) )
	    	return $response->withStatus(404, 'Authorization failed: invalid credentials.');

	    if( !$User->isAdminUser() )
            return $response->withStatus(404, 'Authorization failed: no admin rights.');

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