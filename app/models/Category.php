<?php

namespace models;

use core\Model as Model;

class Category extends Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'category';
    public static $COLUMNS = ['id', 'name', 'name_short', 'context', 'date_created'];

    public static function getGame($game_id)
    {
        return static::getOne([
            'context' => 'game',
            'id' => $game_id
        ]);
    }

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getNameShort()
    {
        return $this->getProperty('name_short');
    }

    public function getContext()
    {
        return $this->getProperty('context');
    }

    public function getDateCreated()
    {
        return $this->getProperty('date_created');
    }

}
