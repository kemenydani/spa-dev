<?php

namespace controllers\api;

use Intervention\Image\ImageManager;
use models\ProductImage;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Product;

class ProductController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Product() );
    }

    public function postDelete(Request $request, Response $response, $beforeDelete = null, $afterDelete = null): Response
    {
        return parent::postDelete($request, $response, function($range)
        {
            foreach($range as $productId)
            {
                $ProductImages = ProductImage::findAll($productId, 'product_id');
                /* @var ProductImage $ProductImage */
                foreach($ProductImages as $ProductImage)
                {
                    $path = ProductImage::IMAGE_PATH . DIRECTORY_SEPARATOR . $ProductImage->getFileName();

                    if(isReadableFile($path) && isReadableFile($path)) unlink(realpath($path));

                    $ProductImage->destroy();
                }
            }
        }, $afterDelete);
    }
    
    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items)
        {
            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$product)
            {
                $urls = [];
                /*
                $Images = ProductImage::findAll($product['id'], 'product_id');

*/
                /* @var ProductImage $ProductImage */
                /*
                foreach($Images as $ProductImage)
                {
                    $path = ProductImage::IMAGE_PATH . DIRECTORY_SEPARATOR . $ProductImage->getFileName();

                    if(!is_readable($path)) continue;

                    $img = $ImageManager->make($path);
                    $urls[] = ['id' => $ProductImage->getId(), 'dataUrl' => $img->encode('data-url')];
                }
*/
                $product['images'] = $urls;
            }

            return $items;
        });
    }

    public function fetchImages(Request $request, Response $response, $args = [])
    {
        $id = $request->getQueryParam('id');

        $ProductImages = ProductImage::findAll($id, 'product_id');

        if(!$ProductImages) $ProductImages = [];

        $res = [];

        /* @var ProductImage $Image */
        foreach ($ProductImages as $Image)
        {
            $res[] = [
                'id' => $Image->getId(),
                'file_name' => $Image->getFileName(),
                'path' => $Image->requestImageUrl()
            ];
        }

        return $response->withJson($res)->withStatus(200);
    }

}