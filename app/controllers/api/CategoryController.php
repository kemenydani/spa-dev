<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Category as Category;

class CategoryController extends Controller implements CRUDInterface
{
    public function __construct()
    {
        parent::__construct( new Category() );
    }

	public function postCreate( Request $request, Response $response )
	{
		$data = $request->getParsedBody();

		$name       = isset($data['name'])       ? $data['name']       : '';
		$name_short = isset($data['name_short']) ? $data['name_short'] : '';
		$context    = isset($data['context'])    ? $data['context']    : '';


		$Category = Category::create([
			'name'       => $name,
			'name_short' => $name_short,
			'context'    => $context,
		]);

		$id = $Category->save();

		if( $id === false ) return $response->withStatus(500, 'Database error: Could not insert Category.');

		return $response->withJson( $Category->getProperties() )->withStatus(200);
	}
	
	public function getOne( Request $request, Response $response )
	{
		$id = $request->getParsedBody()['id'];
		
		$Category = Category::find( $id );
		
		if( $Category === null ) return $response->withStatus(500, 'Database error: Could not find Category.');
		
		return $response->withJson( $Category->getProperties() )->withStatus(200);
	}
	
	public function getAll( Request $request, Response $response )
	{
		return $response->withJson(Category::all());
	}
	
}