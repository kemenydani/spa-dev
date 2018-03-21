<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class UserController extends ViewController
{

    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.user.profile.html.twig');
    }

    public function auth ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.user.auth.html.twig');
    }

    public function uploadPicture ( Request $request, Response $response, $args )
    {
        $file = $request->getUploadedFiles()['profile_picture'];
        if( $file ) return self::uploadImage( $file, 'profile_picture' );
        return $response->withStatus('500', 'Invalid File');
    }

    public function uploadCover ( Request $request, Response $response, $args )
    {
        $file = $request->getUploadedFiles()['cover_picture'];
        if( $file ) return self::uploadImage( $file, 'cover_picture' );
        return $response->withStatus('500', 'Invalid File');
    }

    private static function uploadImage( $file, $dir )
    {
        if($file) return FileUploadController::upload( $file, 'images/users/16/' . $dir );
        return false;
    }
}