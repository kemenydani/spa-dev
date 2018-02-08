<?php

namespace controllers\api;

use core\Model as Model;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class Controller implements BaseControllerInterface
{
    protected $Model;

    public function getModel()
    {
        return $this->Model;
    }

    public function __construct( Model $Model )
    {
        $this->Model = $Model;
    }

    public function postDelete( Request $request, Response $response )
    {
        $range = $request->getParsedBody()['range'];

        $isDeleted = $this->Model::deleteIn('id', $range );

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

        $Model = $this->Model::find( $id );

        if( $Model === null ) return $response->withStatus(500, 'Database error: Could not find article.');

        $data = $Model->getProperties();

        return $response->withJson( $data )->withStatus(200);
    }

    public function getAll( Request $request, Response $response)
    {
        $Models = $this->Model::all();

        $data = [];

        foreach( $Models as $Model )
        {
            $data[] = $Model ->getPublicProperties();
        }

        return $response->withJson( $data );
    }

}