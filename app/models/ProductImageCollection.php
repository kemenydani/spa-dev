<?php

namespace models;

use core\ModelCollection;
use models\ProductImage;

class ProductImageCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, ProductImage::class));
    }

    public function getUrlArray()
    {
        $arr = [];

        /* @var \models\ProductImage $ProductImage */
        foreach($this->getModels() as $ProductImage) $arr[] = $ProductImage->requestImageUrl();

        return $arr;
    }
    
}
