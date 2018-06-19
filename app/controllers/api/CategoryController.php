<?php

namespace controllers\api;

use models\Category as Category;

class CategoryController extends ModelController
{

    public function __construct()
    {
        parent::__construct( new Category() );
    }

}