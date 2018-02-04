<?php

namespace models;

use core\Model as Model;

class Category extends Model
{
	public $permissions = [];
	public static $_UNIQUE_KEY = 'id';
	public static $_TABLE = 'category';
	
	
	
}