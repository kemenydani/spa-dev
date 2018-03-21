<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Slim\Http\UploadedFile;

function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

class FileUploadController extends Controller
{
    const __UPLOADS__ = __ROOT__ . '/storage/uploads/';

    public function uploadImage ( Request $request, Response $response )
    {
        $uploadedFiles = $request->getUploadedFiles();

        $uploadedFile = $uploadedFiles['image'];

        $finalDir = self::upload($uploadedFile, 'images');

        if($finalDir){
            return $response->withJson(['upload_path' => $finalDir])->withStatus(200);
        }
        return $response->withStatus(500);
    }

    public static function upload( $tmpFile = null, $path = "" )
    {
        if(!$tmpFile) return false;

        $directory = __UPLOADS__ . '/' . $path;

        if( $tmpFile->getError() !== UPLOAD_ERR_OK) return false;

        if ( !file_exists( $directory ) ) mkdir( $directory, 0777, true );

        $filename = moveUploadedFile( $directory, $tmpFile );

        return $directory . $filename;
    }
}
