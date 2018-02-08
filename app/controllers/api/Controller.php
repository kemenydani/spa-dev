<?php

namespace controllers\api;

use core\Model as Model;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class Controller
{
    public static $Model;

    public function postDelete( Request $request, Response $response )
    {
        $range = $request->getParsedBody()['range'];

        $Model = Model::make( static::$Model );

        $isDeleted = $Model::deleteIn('id', $range );

        if( !$isDeleted ) return $response->withStatus( 500, 'Database error: Could not delete items.' );

        return $response->withJson( $range );
    }

    public function postCreate( Request $request, Response $response )
    {

    }

    public function postUpdate( Request $request, Response $response )
    {

    }

    public function getOne( Request $request, Response $response )
    {
        $id = $request->getParsedBody()['id'];

        $Model = Model::make( static::$Model );

        $ModelInstance = $Model::find( $id );

        if( $ModelInstance === null ) return $response->withStatus(500, 'Database error: Could not find article.');

        $data = $ModelInstance->getProperties();

        return $response->withJson( $data )->withStatus(200);
    }

    public function getAll( Request $request, Response $response)
    {
        $Model = Model::make( static::$Model );

        $Models = $Model::all();

        $data = [];

        foreach( $Models as $ModelInstance )
        {
            $data[] = $ModelInstance ->getPublicProperties();
        }

        return $response->withJson( $data );
    }

}