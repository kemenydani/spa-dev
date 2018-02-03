<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Category as Category;

class CategoryController extends Controller
{
	public function postCreate( Request $request, Response $response )
	{
		$name      = $request->getParsedBody()['name'];
		$name_short     = $request->getParsedBody()['name_short'];
		$context    = $request->getParsedBody()['context'];
		
		$Category = Category::create([
			'name'       => $name,
			'name_short' => $name_short,
			'context'    => $context,
		]);
		
		$id = $Category->save();
		
		if( $id === false ) return $response->withStatus(500, 'Database error: Could not insert Category.');
		
		return $response->withJson( [ 'id' => $id ] )->withStatus(200);
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