<?php

namespace controllers\api;

use Intervention\Image\ImageManager;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class ImageUploadController
{
    static $format = 'jpg';
    static $unique = false;

    public static function upload(Request $request, Response $response, $destinationPath = null, $callback, $returnUrl = false, $prefix = null )
    {
        $files = $request->getUploadedFiles();

        $ImageManager = new ImageManager(array('driver' => 'gd'));

        $images = [];
        /* @var $uploadedFile UploadedFile */
        foreach($files as $name => $file)
        {
            $unique = static::$unique ? '_' . strtolower(randomString(15)) . '_' : '';

            $uploadedFile = $file;

            //$extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $basename = basename($uploadedFile->getClientFilename());

            $fileName = $unique . '_' . md5(sanitize($basename, true, true)) . "." . static::$format;

            if($prefix) $fileName = $prefix . '_' . $fileName;

            $img = $ImageManager->make($uploadedFile->getStream());

            $img->interlace(true);

            $finalPath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;

            $img->encode(static::$format);

            try {
                $img->save((string)$finalPath);
            } catch( \Exception $e ){
                continue;
            }

            $payload = $callback($fileName);

            $img->encode('data-url');
            $images[$name]['file_name'] = $fileName;
            $images[$name]['encoded'] = $img->getEncoded();
        }

        if($returnUrl) return $response->withStatus(200)->withJson(['url' => $payload]);

        return $response->withStatus(200)->withJson($images);
    }

}