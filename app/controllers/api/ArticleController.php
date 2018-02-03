<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Article as Article;
use core\Auth as Auth;

class ArticleController extends Controller
{
	public function postCreate( Request $request, Response $response )
	{
		$data = $request->getParsedBody();
		
		$title   = isset($data['title'])   ? $data['title']   : '';
		$teaser  = isset($data['teaser'])  ? $data['teaser']  : '';
		$content = isset($data['content']) ? $data['content'] : '';
		
		$Article = Article::create([
			'title'      => $title,
			'teaser'     => $teaser,
			'content'    => $content,
			'created_by' => Auth::user()->getId()
		]);
		
		$id = $Article->save();
		
		if( $id === false ) return $response->withStatus(500, 'Database error: Could not insert article.');
		
		return $response->withJson( $Article->getProperties() )->withStatus(200);
	}
	
	public function getOne( Request $request, Response $response )
	{
		$id = $request->getParsedBody()['id'];
		
		$Article = Article::find( $id );
		
		if( $Article === null ) return $response->withStatus(500, 'Database error: Could not find article.');
		
		return $response->withJson( $Article->getProperties() )->withStatus(200);
	}
	
	public function getAll( Request $request, Response $response )
	{
		return $response->withJson(Article::all());
	}
	
}