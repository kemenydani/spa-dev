<?php

namespace models;

use \core\Model as Model;

class SquadMember extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'squad_member';

    public static $_PROPS = ['id','squad_id', 'user_id'];
    public static $_PROPS_PROTECTED = [];

    public function getSquads() : array
    {
        return Squad::findAll( $this->getProperty('id') );
    }

}
