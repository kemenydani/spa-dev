<?php

namespace controllers\api;

use models\ProductImage;
use Slim\Http\Request;
use Slim\Http\Response;

class ProductImageController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new ProductImage() );
    }

    public function postDelete( Request $request, Response $response, $beforeDelete = null, $afterDelete = null ): Response
    {
        return parent::postDelete($request, $response, function( $range )
        {
            foreach($range as $id)
            {
                /* @var ProductImage $ProductImage */
                $ProductImage = ProductImage::find($id);

                if(!$ProductImage) continue;

                $path = ProductImage::IMAGE_PATH . DIRECTORY_SEPARATOR . $ProductImage->getFileName();

                if(isReadableFile($path) && isReadableFile($path)) unlink(realpath($path));
            }
        });
    }

}
