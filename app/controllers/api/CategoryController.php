<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Category as Category;

class CategoryController extends ModelController
{

    public function __construct()
    {
        parent::__construct( new Category() );
    }

}