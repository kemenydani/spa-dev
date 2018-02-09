<?php

namespace models;

use core\Model as Model;

class Video extends Model
{
	public static $_UNIQUE_KEY = 'id';
	public static $_TABLE = 'video';

    public static $_PROPS = ['id', 'title', 'date_created'];
    public static $_PROPS_SEARCHABLE = ['id', 'title', 'date_created'];

}