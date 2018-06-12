<?php

namespace models;

use core\ModelCollection;

class SettingCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Setting::class));
    }

}
