<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\User;

class ImageController extends Controller
{
    public function getUserPicture( Request $request, Response $response, $args )
    {
        $fileName = $args['filename'];
        $path = User::IMAGE_PATH . '/' . $fileName;
        $type = mime_content_type($path);
        readfile($path);
        return $response->withHeader('Content-Type', $type)->withHeader('Content-Length', filesize($path));
    }
}