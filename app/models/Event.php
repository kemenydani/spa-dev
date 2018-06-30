<?php

namespace models;

use core\Model as Model;
use models\Match as Match;
use core\DB;

class Event extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'event';
    public static $COLUMNS = [
        'id',
        'name',
        'website',
        'active',
        'description',
        'comments_enabled',
        'start_date',
	    'end_date',
    ];

    public static $SEARCH_COLUMNS = ['name', 'website'];

    public function getMatches()
    {
    	return Match::findAll($this->getId(), 'event_id');
    }

    public function setSquadPivots($postIds)
    {
        $sql = DB::instance()->getAll('SELECT squad_id FROM `_xyz_event_squad_pivot` WHERE event_id = ?', $this->getId());
        $currentIds = [];
        if(is_array($sql)) foreach ($sql as $item) $currentIds[] = $item['squad_id'];
        
        foreach($postIds as $id)
        {
            if(in_array($id, $currentIds)) continue;
            DB::instance()->insert('event_squad_pivot', ['event_id' => $this->getId(), 'squad_id' => $id]);
        }

        foreach($currentIds as $id)
        {
            if(in_array($id, $postIds)) continue;
            DB::instance()->delete('event_squad_pivot', 'squad_id', $id);
        }

        return $postIds;
    }
    
    public function getArticles()
    {
        $id = $this->getId();

        $stmt = " SELECT a.* FROM _xyz_event_article_pivot eap " .
                " LEFT JOIN _xyz_article a ON a.id = eap.article_id " .
                " WHERE eap.event_id = $id "
        ;

        // TODO: where active

        $ArticleCollection = ArticleCollection::queryToCollection($stmt);

        return $ArticleCollection;
    }

    public function getSquads()
    {
        $id = $this->getId();

        $stmt = " SELECT a.* FROM _xyz_event_squad_pivot esp " .
            " LEFT JOIN _xyz_squad a ON a.id = esp.squad_id " .
            " WHERE esp.event_id = $id "
        ;

        // TODO: where active

        $SquadCollection = SquadCollection::queryToCollection($stmt);

        return $SquadCollection;
    }

    public function getComments()
    {
        $id = $this->getId();

        $stmt = " SELECT c.*, c.id as id, u.username, u.profile_picture FROM _xyz_event_comment_pivot ecp " .
                " LEFT JOIN _xyz_comment c ON c.id = ecp.comment_id " .
                " LEFT JOIN _xyz_user u ON u.id = c.user_id " .
                " WHERE ecp.event_id = $id "
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

        $success = DB::pivot('event_comment_pivot', [
            'event_id' => $this->getProperty('id'),
            'comment_id' => $Comment->getProperty('id')
        ]);

        $success ? DB::instance()->commit() : DB::instance()->rollBack();

        return $Comment;
    }
    
    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getCommentsEnabled()
    {
        return $this->getProperty('comments_enabled');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getWebsite()
    {
        return $this->getProperty('website');
    }

    public function getStartDate()
    {
        return $this->getProperty('start_date');
    }
	
	public function getEndDate()
	{
		return $this->getProperty('end_date');
	}

}