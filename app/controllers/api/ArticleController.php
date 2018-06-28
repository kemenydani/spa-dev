<?php

namespace controllers\api;

use core\DB;
use Intervention\Image\ImageManager;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Article as Article;
use core\Auth as Auth;
use models\Comment as Comment;

class ArticleController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Article() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items){

            $q = "SELECT category_id FROM _xyz_article_category_pivot WHERE article_id = ? ";

            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$article)
            {
                $categories = DB::instance()->getAll($q, $article['id']);

                $idArr = [];

                if(is_array($categories)) foreach($categories as $key => $value) $idArr[] = (string)$value['category_id'];

                $article['categories'] = $idArr;
                $path = Article::IMAGE_PATH . DIRECTORY_SEPARATOR . $article['headline_image'];

                $article['imageDataUrl'] = null;

                if(!isReadableFile($path)) continue;

                $img = $ImageManager->make($path);
                $img->encode('data-url');
                $article['imageDataUrl'] = $img->getEncoded();
            }

            return $items;
        });
    }

    public function postComment( Request $request, Response $response ) : Response
    {
        /* @var Article $Article */
        $data = $request->getParsedBody();

        $Article = Article::find($data['article_id']);

        if(!$Article) return $response->withStatus(404, 'Article not found');

        $data['user_id'] = 16; // auth user

        $Comment = $Article->addComment( Comment::create( $data ) );

        $result = [];

        if( $Comment->getProperty('id'))
        {
            $result = $Comment->getProperties();
        }

        return $response->withJson( $result );
    }

    public function postStore(Request $request, Response $response): Response
    {
        $formData = $request->getParsedBody();

        $formData['title_seo'] = url_slug($formData['title']);

        $errors = []; //$this->Model::validate($formData);

        if(count($errors)) return $response->withStatus(200)->withJson(['success' => false, 'data' => $errors]);

        $Model = $this->Model::create($formData);

        $id = $Model->save();

        if($id) return $response->withStatus(200)->withJson(['success' => true, 'data' => $Model->getFormatted()]);
        return $response->withStatus(500, 'Server Error: Could not save.');
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
			'title_seo'  => url_slug($title),
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