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

    public function uploadPicture ( Request $request, Response $response, $args )
    {
        $files = $request->getUploadedFiles();

        if( array_key_exists('profile_picture', $files ) )
        {
            return FileUploadController::upload( $files['profile_picture'], 'images/users/16/profile_picture' );
        }
        return $response->withStatus(404, 'Invalid File Key');
    }

    public function uploadCover ( Request $request, Response $response, $args )
    {
        $files = $request->getUploadedFiles();

        if( array_key_exists('cover_picture', $files ) )
        {
            return FileUploadController::upload( $files['cover_picture'], 'images/users/16/cover_picture' );
        }
        return $response->withStatus(404, 'Invalid File Key');
    }

}