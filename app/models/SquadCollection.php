<?php

namespace models;

use core\ModelCollection;
use models\Squad as Squad;

class SquadCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Squad::class));
    }

    public function getProperties($set = []): array
    {
	   
    }
	
}
