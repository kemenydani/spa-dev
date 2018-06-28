<?php

namespace controllers\api;
use Slim\Http\Request;
use Slim\Http\Response;
use models\GalleryImage;

class GalleryImageController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new GalleryImage() );
    }

    public function postDelete( Request $request, Response $response, $beforeDelete = null, $afterDelete = null ): Response
    {
        return parent::postDelete($request, $response, function( $range )
        {
            foreach($range as $id)
            {
                /* @var GalleryImage $GalleryImage */
                $GalleryImage = GalleryImage::find($id);

                if(!$GalleryImage) continue;

                $path = GalleryImage::IMAGE_PATH . DIRECTORY_SEPARATOR . $GalleryImage->getFileName();

                if(isReadableFile($path) && isReadableFile($path)) unlink(realpath($path));
            }
        });
    }

}