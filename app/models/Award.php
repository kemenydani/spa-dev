<?php

namespace models;

use core\Model as Model;
use models\Squad as Squad;

class Award extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'award';
    public static $COLUMNS = [
        'id',
        'event_name',
        'place',
        'award_date',
	    'squad_id',
        'description'
    ];
	
	static $SEARCH_COLUMNS = ['event_name', 'place'];
    
    public function getSquad()
    {
    	return Squad::find(['squad_id' => $this->getSquadId()]);
    }
    
    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getSquadId()
    {
        return $this->getProperty('squad_id');
    }

    public function getEventName()
    {
        return $this->getProperty('event_name');
    }

    public function getDescription()
    {
        return $this->getProperty('description');
    }

    public function getAwardDate()
    {
        return $this->getProperty('award_date');
    }
	
	public function getPlace()
	{
		return $this->getProperty('place');
	}

}