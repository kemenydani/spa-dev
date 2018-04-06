<?php

namespace models;

use core\ModelCollection;
use models\UserCollection as UserCollection;
use models\User as User;

class _CommentCollection extends ModelCollection
{
    /**
     * @var Comment[] $models List of Model objects.
     */
    public $models = [];

	/**
	 * @var UserCollection
	 */
	protected $UserCollection;

	public function __construct( $comments = [] )
    {
        $this->collectCommentArray($comments);
        $this->UserCollection = new UserCollection( $this->getDistinctUsers( $this->getModels() ) );
    }

    /**
     * @param array $comments
     * @return $this
     */
    public function collectCommentArray( array $comments = [] )
	{
		foreach( $comments as $key => $value)
		{
		    if(is_array($value)) $value = Comment::create($value);
            $this->collectModel($value, $key);
        }
		return $this;
	}

    /**
     * @param Comment[]
     * @return User[]
     */
	private function getDistinctUsers( array $comments )
	{
        $distinctUsers = [];

		foreach( $comments as $Comment )
		{
			if(!array_key_exists($Comment->getProperty('user_id'), $distinctUsers))
			{
                $distinctUsers[$Comment->getProperty('user_id')] = $Comment->getUser();
            }
		}
		return $distinctUsers;
	}

	public function getCommentUser($user_id)
    {
        return $this->UserCollection->get($user_id);
    }

    public function withUser()
    {
	    foreach($this->getModels() as $Comment )
	    {
	        $Comment->setProperty('user', $this->UserCollection->get($Comment->getProperty('user_id')));
        }
        return $this;
    }

	public function toTree()
	{
	    $comments = $this->toArray();

        foreach ($comments as $k => &$v) {
            if ($v['pid'] != 0) {
                if(!array_key_exists('ch', $comments[$v['pid']])) $comments[$v['pid']]['ch'] = array();
                $comments[$v['pid']]['ch'][$v['id']] = &$v;
            }
        }

        unset($v);

        foreach ($comments as $k => $v) {
            if ($v['pid'] != 0) {
                unset($comments[$k]);
            }
        }

        $comments = array_reverse_recursive($comments);

        return $comments;
	}

	public function users() : UserCollection
	{
		return $this->UserCollection;
	}
	
}
