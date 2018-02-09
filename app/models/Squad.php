<?php

namespace models;

use \core\Model as Model;

class Squad extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'squad';

    public static $_PROPS = ['id', 'name'];
    public static $_PROPS_PROTECTED = [];

    public function getMembers() : array
    {
        return Squad::findAll( $this->getProperty('id') );
    }

}
