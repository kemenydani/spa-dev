<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use models\Product;

class ProductController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Product() );
    }

}