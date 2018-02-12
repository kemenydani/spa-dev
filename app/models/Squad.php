<?php

namespace models;

use \core\Model as Model;
use \models\SquadMember as SquadMember;

class Squad extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'squad';

    public static $_PROPS = ['id', 'name'];
    public static $_PROPS_PROTECTED = [];

    public function getMembers() : array
    {
        return SquadMember::findAll( $this->getProperty('id'), 'squad_id' );
    }

}
