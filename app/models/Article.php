<?php

namespace models;

use core\Model as Model;
use core\DB as DB;

use models\Category as Category;
use models\Comment as Comment;

class Article extends Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'article';

	public static $COLUMNS = [
	    'id',
        'active',
        'title',
        'title_seo',
        'headline_image',
        'teaser',
        'content',
        'activation_time',
        'date_created',
        'created_by',
		'comments_enabled',
        'event_id'
    ];

    static $SEARCH_COLUMNS = ['title', 'teaser', 'date_created'];

    const IMAGE_PATH = __UPLOADS__ . '/images/article';

    public function setCategoryPivots($postIds)
    {
        $sql = DB::instance()->getAll('SELECT category_id FROM `_xyz_article_category_pivot` WHERE article_id = ?', $this->getId());
        $currentIds = [];
        if(is_array($sql)) foreach ($sql as $item) $currentIds[] = $item['category_id'];

        foreach($postIds as $id)
        {
            if(in_array($id, $currentIds)) continue;
            DB::instance()->insert('article_category_pivot', ['article_id' => $this->getId(), 'category_id' => $id]);
        }

        foreach($currentIds as $id)
        {
            if(in_array($id, $postIds)) continue;
            DB::instance()->delete('article_category_pivot', 'category_id', $id);
        }

        return $postIds;
    }

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
				DB::instance()->insert('article_categories', [ 'article_id' => $article_id, 'category_id' => $category_id ] );
			}
		}
		
		DB::instance()->commit();
	}

    public function getArticleImage()
    {
        return '/article_headline/' . $this->getProperty('headline_image');
    }

    public function getHeadlineImage()
    {
        return $this->getProperty('headline_image');
    }

    public function getTitle()
    {
        return $this->getProperty('title');
    }


    public function readLink()
    {
	    return '/article/read/' . $this->getTitle();
    }
    
    public function formatHeadlineImage()
    {
        return '/article_headline/' . $this->getProperty('headline_image');
    }

    public function getId()
    {
	    return $this->getProperty('id');
    }

    public function getEventId()
    {
        return $this->getProperty('event_id');
    }

	public function getCommentsEnabled()
	{
		return $this->getProperty('comments_enabled');
	}

    //TODO:: create commentable trait
	public function getComments()
    {
        $id = $this->getId();

        $stmt = " SELECT c.*, c.id as id, u.username, u.profile_picture FROM _xyz_article_comment_pivot acp " .
                " LEFT JOIN _xyz_comment c ON c.id = acp.comment_id " .
                " LEFT JOIN _xyz_user u ON u.id = c.user_id " .
                " WHERE acp.article_id = $id "
        ;

        $sql = DB::instance()->query( $stmt );

        $rows = $sql->fetchAll(\PDO::FETCH_UNIQUE|\PDO::FETCH_ASSOC );

        $result = CommentCollection::toTree( $rows  );

        return $result;
    }

    //TODO:: create commentable trait
    public function addComment( Comment $Comment )
    {
        DB::instance()->beginTransaction();

	    $Comment->save();

        $success = DB::pivot('article_comment_pivot', [
            'article_id' => $this->getProperty('id'),
            'comment_id' => $Comment->getProperty('id')
        ]);

        $success ? DB::instance()->commit() : DB::instance()->rollBack();

        return $Comment;
    }
}
