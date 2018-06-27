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

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items)
        {
            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$product)
            {
                $Images = ProductImage::findAll($product['id'], 'product_id');

                $urls = [];
                /* @var \models\GalleryImage $Image */
                //if(is_array($Images)) foreach($Images as $Image) $urls[] = 'http://phpapp' . (string)$Image->requestImageUrl();

                foreach($Images as $Image) {
                    $path = $Image::IMAGE_PATH . DIRECTORY_SEPARATOR . $Image->getFileName();
                    $img = $ImageManager->make($path);
                    $urls[] = ['id' => $Image->getId(), 'dataUrl' => $img->encode('data-url')];
                }

                $product['images'] = $urls;
            }

            return $items;
        });
    }

}