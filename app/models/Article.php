<?php

namespace models;

use core\Model as Model;
use core\DB as DB;

use models\Category as Category;

class Article extends Model
{

	public static $_UNIQUE_KEY = 'id';
	public static $_TABLE = 'article';

	public static $_PROPS = ['id', 'active', 'title', 'teaser', 'content', 'activation_time', 'date_created'];
    public static $_PROPS_READONLY = ['id', 'date_created'];
    public static $_PROPS_SEARCHABLE = ['id', 'title', 'teaser', 'content', 'date_created', 'activation_time'];
    public static $_PROPS_PROTECTED = ['content'];
    /*
    public static $_RELATIONS = [
        'categories' => [
            'model'  => Category::class,
            'type'   => 'hasmany',
            'pivot'  => 'article_categories',
            'column' => 'article_id'
        ]
    ];
    */
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

    public function getArticles()
    {

    }

	/*
	public function getCategories()
	{
		$id = $this->getId();
		
		$stmt = " SELECT c.* FROM _xyz_article_categories ac" .
				" LEFT JOIN _xyz_categories c " .
				" ON c.category_id = ac.category_id " .
		        " WHERE ac.article_id = $id"
		;
		
		$sql = DB::instance()->query( $stmt );

		return $sql->fetchAll(\PDO::FETCH_OBJ );
	}
*/
	public function getComments()
    {

    }

    public function getViews()
    {

    }

    public function getLikes()
    {

    }
	
}