<?php

namespace models;

use core\Model as Model;
use models\Match as Match;

class Event extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'event';
    public static $COLUMNS = [
        'id',
        'name',
        'website',
        'start_date',
	    'end_date',
    ];

    public function getMatches()
    {
    	return Match::getAll(['event_id' => $this->getId()]);
    }
    
    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getWebsite()
    {
        return $this->getProperty('website');
    }

    public function getStartDate()
    {
        return $this->getProperty('start_date');
    }
	
	public function getEndDate()
	{
		return $this->getProperty('end_date');
	}

}