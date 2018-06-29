<?php

namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;

use core\DB as DB;

use core\Auth;

use models\Article as Article;
use models\ArticleCollection;
use models\Comment as Comment;

class ArticleController extends ViewController
{
    const INFINITE_LIMIT = 6;

    public function index ( Request $request, Response $response )
    {
        $q1 = " SELECT * FROM _xyz_article art " .
            " WHERE art.highlighted = ? "      .
            " ORDER BY art.date_created DESC " .
            " LIMIT 2 "
        ;

        $headlines = (ArticleCollection::queryToCollection($q1, 1))->getFormatted();

	    $data  = $this->getMore();

        $this->view->render($response, 'route.view.article.list.html.twig',
            [
                'headlines' => $headlines,
                'articles' => json_encode($data),
                'limit' => static::INFINITE_LIMIT
            ]
        );
    }

    public function getLoadInfinite( Request $request, Response $response )
    {
	    $search = $request->getQueryParam('search');
        $orderBy = $request->getQueryParam('orderBy');
	    $startAt = $request->getQueryParam('startAt') ? $request->getQueryParam('startAt') : 0;
	    
	    $data = $this->getMore($search, $startAt, $orderBy);

	    return $response->withStatus(200)->withJson($data);
    }

    protected function getMore($search = null, $startAt = 0, $orderBy = "")
    {
        $params = [];
        $where = "";

        $order = "ORDER BY id DESC";

        if(strlen($orderBy))
        {
            $orderBy = explode('|', $orderBy);
            if(count($orderBy))
            {
                // TODO: change this to date
                $order = " ORDER BY id {$orderBy[1]} ";
            }
        }

	    if($search)
	    {
		    $params['name'] = '%'. $search .'%';
		    $where = ' WHERE title LIKE :name OR teaser LIKE :name ';
	    }

        $q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_article " .
            " {$where} " .
            " {$order} " .
            " LIMIT ".static::INFINITE_LIMIT." OFFSET " . (int)$startAt
        ;

        $articles = (ArticleCollection::queryToCollection($q1, $params))->getFormatted();

        $total = DB::instance()->totalRowCount();

        return ['articles' => $articles, 'totalItems' => $total, 'q' => $q1];
    }
    
    public function postComment( Request $request, Response $response )
    {
        $formData = $request->getParsedBody();

        $Article = Article::find($formData['model_id']);

        if(!$Article) return $response->withStatus(404, 'Article not found');

        $User = Auth::user();

        $formData['user_id'] = $User->getProperty('id');

        /* @var Comment $Comment */
        $Comment = $Article->addComment( Comment::create( $formData ) );

        $data = $Comment->getProperties();
        $data['username'] = $User->getUsername();
        $data['profile_picture'] = $User->requestProfilePicture();

        return $response->withJson( $data );
    }

    public function read ( Request $request, Response $response, $args )
    {
        $article = Article::find($args['title_seo'], 'title_seo');

        if(!$article) return false;

        $comments = $article->getComments();

        $this->view->render(
            $response,
            'route.view.article.read.html.twig',
            [
                'article'  => $article->getProperties(),
                'comments' => json_encode($comments, true)
            ]
        );
    }
}
