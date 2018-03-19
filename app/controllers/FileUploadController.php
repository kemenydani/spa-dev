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
    public static  $__DEST__ = __ROOT__ . '/storage/uploads/images';

    public function uploadImage ( Request $request, Response $response )
    {
        $uploadedFiles = $request->getUploadedFiles();

        $uploadedFile = $uploadedFiles['image'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile( self::$__DEST__ , $uploadedFile);
            return $response->withJson(['upload_path' => self::$__DEST__ . $filename])->withStatus(200);
        }
        return $response->withStatus(500);
    }
}
