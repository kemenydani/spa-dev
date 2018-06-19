<?php

namespace models;

use core\Model as Model;

class Context extends Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'context';
    public static $COLUMNS = ['id', 'name'];

    static $SEARCH_COLUMNS = ['name'];

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

}
