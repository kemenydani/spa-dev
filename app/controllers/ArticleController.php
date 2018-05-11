<?php

namespace controllers;

use models\ArticleCollection as ArticleCollection;;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\DB as DB;
use models\Article as Article;
use models\Comment as Comment;
use models\User as User;

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
	    $startAt = $request->getQueryParam('startAt') ? $request->getQueryParam('startAt') : 0;
	    
	    $data = $this->getMore($search, $startAt);

	    return $response->withStatus(200)->withJson($data);

    }

    protected function getMore($search = [], $startAt = 0)
    {
        $params = [];
        $where = "";
        $i = 0;
        foreach($search as $key => $value)
        {
            if(empty($value)) continue;
            $params[] = $value;
            $where .= $i === 0 ? ' WHERE ' . $key . ' = ? ' : ' AND ' . $key . ' = ? ';
            $i++;
        }

        $q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_article " .
            " {$where} " .
            " ORDER BY date_created DESC " .
            " LIMIT ".static::INFINITE_LIMIT." OFFSET " . (int)$startAt
        ;

        $articles = (ArticleCollection::queryToCollection($q1, $params))->getFormatted();

        $total = DB::instance()->totalRowCount();

        return ['models' => $articles, 'total' => $total];
    }
    
    public function postComment( Request $request, Response $response )
    {
        $formData = $request->getParsedBody();

        $Article = Article::find($formData['model_id']);

        if(!$Article) return $response->withStatus(404, 'Article not found');

        $User = User::find(1);

        $formData['user_id'] = $User->getProperty('id');

        $Comment = $Article->addComment( Comment::create( $formData ) );

        $result = [];

        if( $Comment->getProperty('id') )
        {
            $result = $Comment->getProperties();
            $result['username'] = $User->getProperty('username');
            $result['profile_picture'] = $User->getProfilePicture();
        }

        return $response->withJson( $result );
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
