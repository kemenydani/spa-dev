<?php

namespace models;

use core\ModelCollection;
use models\GalleryImage as GalleryImage;

class GalleryImageCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, GalleryImage::class));
    }
    
}
