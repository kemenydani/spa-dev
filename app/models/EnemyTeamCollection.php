<?php

namespace models;

use core\ModelCollection;
use models\EnemyTeam as EnemyTeam;

class EnemyTeamCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, EnemyTeam::class));
    }

}
