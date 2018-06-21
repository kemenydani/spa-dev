<?php

namespace controllers\api;

use Intervention\Image\ImageManager;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class ImageUploadController
{
    public static function upload(Request $request, Response $response, $destinationPath = null, $callback )
    {
        $files = $request->getUploadedFiles();

        $ImageManager = new ImageManager(array('driver' => 'gd'));

        /* @var $uploadedFile UploadedFile */
        $uploadedFile = $files['image'];

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = basename($uploadedFile->getClientFilename());

        //TODO:: pathinfo bug, fix: double fake extansion needed
        $fileName = '_' . md5(sanitize($basename, true, true)) . ".jpg.jpg";

        $img = $ImageManager->make($uploadedFile->getStream());

        $img->interlace(true);

        $finalPath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;

        $img->encode('jpg');

        $img->save((string)$finalPath);

        $savedPath = $callback($fileName);

        return $response->withStatus(200)->withJson(['path' => $savedPath]);
    }
}