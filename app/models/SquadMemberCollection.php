<?php

namespace models;

use core\ModelCollection;
use models\SquadMember as SquadMember;

class SquadMemberCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, SquadMember::class));
    }

    public function getProperties($set = []): array
    {
	   
    }
	
}
