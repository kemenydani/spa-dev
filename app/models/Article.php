<?php

namespace models;

use core\Model as Model;

class Article extends Model
{
	public $permissions = [];
	public static $_UNIQUE_KEY = 'id';
	public static $_TABLE = 'article';

}