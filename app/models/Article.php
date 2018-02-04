<?php

namespace models;

use core\Model as Model;
use core\DB as DB;

class Article extends Model
{
	public $permissions = [];
	
	public static $_FIELDS = ['id', 'title', 'teaser', 'content'];
	public static $_UNIQUE_KEY = 'id';
	public static $_TABLE = 'article';
	
	public function categorize( array $new_categories )
	{
		$article_id = $this->getId();
		
		$categories_sql = DB::instance()->query("SELECT category_id FROM _xyz_article_categories WHERE article_id = $article_id");
		
		$current_categories = $categories_sql->fetchAll(\PDO::FETCH_NUM);
		
		DB::instance()->beginTransaction();
		
		foreach( $current_categories as $category_id )
		{
			if( !in_array( $category_id, $new_categories ) )
			{
				DB::instance()->query("DELETE FROM _xyz_article_categories WHERE article_id = { $article_id } AND category_id = $category_id");
			}
		}
		
		foreach( $new_categories as $category_id )
		{
			if( !in_array( $category_id, $current_categories ) )
			{
				DB::insert('article_categories', [ 'article_id' => $article_id, 'category_id' => $category_id ] );
			}
		}
		
		DB::instance()->commit();
	}
	
	public function setCategories()
	{
	
	}
	
	public function getCategories()
	{
		$article_id = $this->getId();
		
		$stmt = " SELECT * FROM _xyz_article_categories ac" .
				" LEFT JOIN _xyz_categories c " .
				" ON c.category_id = ac.category_id " .
		        " WHERE ac.article_id = $article_id"
		;
		
		$categories = DB::instance()->query( $stmt );
		
		if( !$categories ) return [];
		
		return $categories;
	}
	
	
	
}