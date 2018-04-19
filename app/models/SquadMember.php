<?php

namespace models;

use \core\Model as Model;

class SquadMember extends Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'squad_member';
	
	public static $COLUMNS = [
		'id',
		'name',
		'squad_id',
        'user_id'
	];

    public function formatName()
    {
        if($this->getName()) return $this->getName();
        return User::find($this->getUserId())->getUserName();
    }

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getSquadId()
    {
        return $this->getProperty('squad_id');
    }

	public function getUserId()
    {
	    return $this->getProperty('user_id');
    }
}
