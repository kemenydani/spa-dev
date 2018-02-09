<?php

namespace models;

use core\Model as Model;

class Category extends Model
{
	public static $_UNIQUE_KEY = 'id';
	public static $_TABLE = 'category';

    public static $_PROPS = ['id', 'name', 'name_short', 'context', 'date_created'];

}