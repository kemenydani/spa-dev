<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use models\Comment as Comment;

class CommentController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Comment() );
    }
}