<?php

namespace models;

use core\DB as DB;
use core\Model as Model;

use models\Article as Article;

class Category extends Model
{
	public static $_UNIQUE_KEY = 'id';
	public static $_TABLE = 'category';

    public static $_PROPS = ['id', 'name', 'name_short', 'context', 'date_created'];
    public static $_PROPS_PROTECTED = [];
    /*
    public static $_RELATIONS = [
        'articles' => [
            'model'  => Article::class,
            'pivot'  => 'article_categories',
            'column' => 'category_id'
        ]
    ];
    */
    public function getArticles()
    {

    }

}