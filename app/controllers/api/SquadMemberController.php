<?php

namespace controllers\api;



use Intervention\Image\ImageManager;
use models\SquadMember;
use Slim\Http\Request;
use Slim\Http\Response;

class SquadMemberController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new SquadMember() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items){

            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$squadMember)
            {
                $path = SquadMember::IMAGE_PATH . DIRECTORY_SEPARATOR . $squadMember['home_avatar'];

                $squadMember['imageDataUrl'] = null;

                if(!isReadableFile($path)) continue;

                $img = $ImageManager->make($path);
                $img->encode('data-url');
                $squadMember['imageDataUrl'] = $img->getEncoded();
            }

            return $items;
        });
    }

}