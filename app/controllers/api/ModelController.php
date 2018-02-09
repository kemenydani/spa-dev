<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use core\Model as Model;

abstract class ModelController implements ModelControllerInterface
{
    protected $Model;

    public function getModel() : Model
    {
        return $this->Model;
    }

    public function __construct( Model $Model )
    {
        $this->Model = $Model;
    }

    public function postDelete( Request $request, Response $response ) : Response
    {
        $range = $request->getParsedBody()['range'];

        $isDeleted = ( $this->Model )::deleteIn('id', $range );

        if( !$isDeleted ) return $response->withStatus( 500, 'Database error: Could not delete items.' );

        return $response->withJson( $range );
    }
 
    public function postCreate( Request $request, Response $response ) : Response
    {
        $Model = $this->Model::create( $request->getParsedBody() );

        $isInserted = $Model->save();

        if( $isInserted === false ) return $response->withStatus(500, 'Database error: Could not insert item.');

        return $response->withJson( $Model->getPublicProperties() )->withStatus(200);
    }

    public function postUpdate( Request $request, Response $response ) : Response
    {

    }

    public function getOne( Request $request, Response $response ) : Response
    {
        $id = $request->getParsedBody()['id'];

        $Model = ( $this->Model )::find( $id );

        if( $Model === null ) return $response->withStatus(500, 'Database error: Could not find article.');

        $data = $Model->getProperties();

        return $response->withJson( $data )->withStatus(200);
    }

    public function getAll( Request $request, Response $response) : Response
    {
        $Models = ( $this->Model )::all();

        $data = [];

        foreach( $Models as $Model )
        {
            $data[] = $Model ->getPublicProperties();
        }

        return $response->withJson( $data );
    }

}
