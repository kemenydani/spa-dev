<?php

namespace controllers\api;

use Intervention\Image\ImageManager;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Partner as Partner;

class PartnerController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Partner() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items){

            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$partner)
            {
                $path = Partner::IMAGE_PATH . DIRECTORY_SEPARATOR . $partner['logo'];

                $partner['imageDataUrl'] = null;

                if(!isReadableFile($path)) continue;

                $img = $ImageManager->make($path);
                $img->encode('data-url');
                $partner['imageDataUrl'] = $img->getEncoded();
            }

            return $items;
        });
    }

}