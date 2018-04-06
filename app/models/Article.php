<?php

namespace models;

use core\Model as Model;
use core\DB as DB;

use models\Category as Category;
use models\Comment as Comment;

class Article extends Model
{
	public static $primaryKey = 'id';
	public static $table = 'article';

	public static $columns = ['id', 'active', 'title', 'title_seo', 'teaser', 'content', 'activation_time', 'date_created'];

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

	public function getComments()
    {
        $stmt = " SELECT c.*, c.id as id FROM _xyz_article_comment_pivot acp" .
                " LEFT JOIN _xyz_comment c " .
                " ON c.id = acp.comment_id " .
                " WHERE acp.article_id = $this->getId()"
        ;

        $sql = DB::instance()->query( $stmt );

        $result = $sql->fetchAll(\PDO::FETCH_UNIQUE|\PDO::FETCH_ASSOC );
        
        $Comments = (new CommentCollection())->collectArray($result);
        
        $result = Comment::formatCommentTree($Comments->toArray());
        
        /*
        $users = [];
        
        foreach($result as &$comment)
        {
            if(array_key_exists($comment['user_id'], $users))
            {
                $comment['user'] = $users[$comment['user_id']];
                continue;
            }

            $user = User::find($comment['user_id']);

            if( $user )
            {
                $comment['username'] = $user->getUsername();
                $comment['profile_picture'] = $user->getProfilePicture();
            }
        }

        $result = Comment::formatCommentTree( $result );
*/
        return $result;
    }

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
