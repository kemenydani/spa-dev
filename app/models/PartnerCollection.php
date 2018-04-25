<?php

namespace models;

use core\ModelCollection;
use models\Partner as Partner;

class PartnerCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Partner::class));
    }

}
