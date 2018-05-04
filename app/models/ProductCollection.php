<?php

namespace models;

use core\ModelCollection;
use models\Product as Product;

class ProductCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Product::class));
    }
    
}
