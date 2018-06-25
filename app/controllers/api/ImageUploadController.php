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

        $result = [];
        $images = [];
        /* @var $uploadedFile UploadedFile */
        foreach($files as $name => $file)
        {
            $uploadedFile = $file;

            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $basename = basename($uploadedFile->getClientFilename());

            $fileName = '_' . md5(sanitize($basename, true, true)) . ".jpg";

            $img = $ImageManager->make($uploadedFile->getStream());

            $img->interlace(true);

            $finalPath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;

            $img->encode('jpg');

            try {
                $img->save((string)$finalPath);
            } catch( \Exception $e ){
                continue;
            }

            $savedPath = $callback($fileName);

            $result[$name] = $fileName;
            $img->encode('data-url');
            $images[$name] = $img->getEncoded();
        }

        return $response->withStatus(200)->withJson(['result' => $result, 'images' => $images]);
    }
}