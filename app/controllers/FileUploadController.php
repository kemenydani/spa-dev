<?php

namespace controllers;
use Slim\Http\UploadedFile;

function moveUploadedFile($directory, UploadedFile $uploadedFile, $secure )
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    if( $secure ) $filename = md5($name) . '.' . $ext;

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

class FileUploadController
{
    const __UPLOADS__ = __ROOT__ . '/storage/uploads/';

    public static function upload( $tmpFile = null, $path = "", $secure = true )
    {
        if(!$tmpFile) return false;

        $directory = __UPLOADS__ . '/' . $path;

        if( $tmpFile->getError() !== UPLOAD_ERR_OK) return false;

        if ( !file_exists( $directory ) ) mkdir( $directory, 0777, true );

        $filename = moveUploadedFile( $directory, $tmpFile, $secure );

        return $directory . '/' . $filename;
    }
}
