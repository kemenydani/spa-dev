<?php

namespace models;

use core\Model2 as Model2;
use core\DB as DB;

class Article2 extends Model2
{
	static $table      = 'article';
	static $primaryKey = 'id';
	static $props      = ['id', 'title', 'teaser', 'content', 'created_by'];
	static $protected  = [];
	static $relations  = [
		'user' => [
			'model'   => User2::class,
			'prop'    => 'created_by',
			'foreign' => 'user_id',
			'type'    => 'hasOne'
		],
		'category' => [
			'model'   => Category2::class,
			'prop'    => 'categories',
			'pivot'   => 'article_category_pivot',
			'foreign' => 'article_id',
			'type'    => 'hasMany'
		]
	];
	
}
