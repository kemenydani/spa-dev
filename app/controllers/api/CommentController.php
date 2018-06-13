<?php

namespace controllers\api;

use models\User;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Comment as Comment;

class CommentController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Comment() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], [ 'user_id' => User::class ] );
    }
}