<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Article as Article;
use core\Auth as Auth;
use core\DB as DB;
use models\Comment as Comment;

class ArticleController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Article() );
    }

    public function postComment( Request $request, Response $response )
    {
        $data = $request->getParsedBody();

        $Article = Article::find($data['article_id']);

        if(!$Article) return $response->withStatus(404, 'Article not found');

        $data['user_id'] = 16; // auth user

        $Comment = $Article->addComment( Comment::create( $data ) );

        $result = [];

        if( $Comment->getProperty('id'))
        {
            $result = $Comment->getPublicProperties();
        }

        return $response->withJson( $result );
    }

    public function postCreate( Request $request, Response $response ) : Response
	{
		$data = $request->getParsedBody();
		
		$title      = isset($data['title'])      ? $data['title']      : '';
		$teaser     = isset($data['teaser'])     ? $data['teaser']     : '';
		$content    = isset($data['content'])    ? $data['content']    : '';
		$categories = isset($data['categories']) ? $data['categories'] : '';
		
		$Article = Article::create([
			'title'      => $title,
			'teaser'     => $teaser,
			'content'    => $content,
			'created_by' => Auth::user()->getId()
		]);

		$id = $Article->save();
		
		$category_ids = [];
		
		foreach( $categories as $key => $value )
		{
			$category_ids[] = $value['id'];
		}
		
		$Article->categorize( $category_ids );
		
		if( $id === false ) return $response->withStatus(500, 'Database error: Could not insert article.');
		
		return $response->withJson( $Article->getProperties() )->withStatus(200);
	}
	
}