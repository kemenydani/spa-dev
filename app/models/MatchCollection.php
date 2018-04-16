<?php

namespace models;

use core\ModelCollection;
use models\Match as Match;

class MatchCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Match::class));
    }

}
