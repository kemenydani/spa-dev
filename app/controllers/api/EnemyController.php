<?php

namespace controllers\api;

use Intervention\Image\ImageManager;
use Slim\Http\Request;
use Slim\Http\Response;

use models\EnemyTeam as EnemyTeam;

class EnemyController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new EnemyTeam() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items){

            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$enemy)
            {
                $path = EnemyTeam::IMAGE_PATH . DIRECTORY_SEPARATOR . $enemy['logo'];

                $enemy['imageDataUrl'] = null;

                if(!isReadableFile($path)) continue;

                $img = $ImageManager->make($path);
                $img->encode('data-url');
                $enemy['imageDataUrl'] = $img->getEncoded();
            }

            return $items;
        });
    }

}