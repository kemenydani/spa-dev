<?php

namespace models;

use \core\Model as Model;
use \models\User as User;

class SquadMember extends Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'squad_member';
	
	public static $COLUMNS = [
		'id',
		'name',
		'squad_id',
        'user_id',
		'home_avatar',
        'desc',
        'position'
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

    public function getHomeAvatar()
    {
	    return $this->getProperty('home_avatar');
    }
	
	public function pathHomeAvatar()
	{
		return self::IMAGE_PATH . DIRECTORY_SEPARATOR . $this->getHomeAvatar();
	}
	
	public function requestHomeAvatar()
	{
		return '/squadMemberHomeAvatar/' . $this->getHomeAvatar();
	}
	
    public function getSquadId()
    {
        return $this->getProperty('squad_id');
    }

	public function getUserId()
    {
	    return $this->getProperty('user_id');
    }

    public function getDesc()
    {
        return $this->getProperty('desc');
    }

    public function getPosition()
    {
        return $this->getProperty('position');
    }

	public function getUser()
	{
		return User::find($this->getUserId());
	}
}
