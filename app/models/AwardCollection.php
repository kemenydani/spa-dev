<?php

namespace models;

use core\ModelCollection;

class AwardCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Award::class));
    }

}
