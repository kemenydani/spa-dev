<?php

namespace models;

use core\ModelCollection;
use models\MatchMap as MatchMap;

class MatchMapCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, MatchMap::class));
    }

}
