<?php

namespace models;

use core\ModelCollection;
use models\Event as Event;

class EventCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Event::class));
    }

}
