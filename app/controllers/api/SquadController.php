<?php

namespace controllers\api;

use Intervention\Image\ImageManager;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Squad as Squad;
use core\Auth as Auth;

class SquadController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Squad() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items){

            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$squad)
            {
                $path = Squad::IMAGE_PATH . DIRECTORY_SEPARATOR . $squad['header_image'];

                $squad['imageDataUrl'] = null;

                if(!isReadableFile($path)) continue;
                // TODO:: solve squad logo thing
                $img = $ImageManager->make($path);
                $img->encode('data-url');
                $squad['imageDataUrl'] = $img->getEncoded();
            }

            return $items;
        });
    }


    public function postCreate( Request $request, Response $response ) : Response
    {
        $data = $request->getParsedBody();

        $title      = isset($data['title'])      ? $data['title']      : '';
        $teaser     = isset($data['teaser'])     ? $data['teaser']     : '';
        $content    = isset($data['content'])    ? $data['content']    : '';
        $categories = isset($data['categories']) ? $data['categories'] : '';

        $Squad = Squad::create([
            'title'      => $title,
            'teaser'     => $teaser,
            'content'    => $content,
            'created_by' => Auth::user()->getId()
        ]);

        $id = $Squad->save();

        $category_ids = [];

        foreach( $categories as $key => $value ) $category_ids[] = $value['id'];

        if( $id === false ) return $response->withStatus(500, 'Database error: Could not insert article.');

        return $response->withJson( $Squad->getProperties() )->withStatus(200);
    }

}