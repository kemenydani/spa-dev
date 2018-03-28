<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\DB as DB;
use models\Article as Article;
use models\Comment as Comment;
use models\User as User;

class ArticleController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.article.list.html.twig');
    }

    public function postComment( Request $request, Response $response )
    {
        $formData = $request->getParsedBody();

        $Article = Article::find($formData['article_id']);

        if(!$Article) return $response->withStatus(404, 'Article not found');

        $User = User::find(1);

        $formData['user_id'] = $User->getProperty('id');

        $Comment = $Article->addComment( Comment::create( $formData ) );

        $result = [];

        if( $Comment->getProperty('id') )
        {
            $result = $Comment->getPublicProperties();
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
                'article'  => $article->getPublicProperties(),
                'comments' => json_encode($comments, true)
            ]
        );
    }
}
