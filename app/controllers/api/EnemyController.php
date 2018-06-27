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
                $img = $ImageManager->make($path);
                $enemy['imageDataUrl'] = $img->encode('data-url');
            }

            return $items;
        });
    }

}