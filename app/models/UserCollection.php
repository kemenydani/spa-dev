<?php

namespace models;

use core\ModelCollection;
use models\User as User;

class UserCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, User::class));
    }


}
