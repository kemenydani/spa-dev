<?php

namespace models;

use core\ModelCollection;
use models\Gallery as Gallery;

class GalleryCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Gallery::class));
    }

}
