<?php

namespace models;

use core\ModelCollection;
use models\UserCollection as UserCollection;
use models\User as User;

class CommentCollection extends ModelCollection
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
        $this->stickDistinctUsers( $this->getModels() );
    }

    /**
     * @param array $comments
     * @return $this
     */
    public function collectCommentArray( array $comments )
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
     */
	private function stickDistinctUsers( array $models )
	{
		$this->UserCollection = new UserCollection();

		foreach( $models as $Comment )
		{
			$user_id = $Comment->getProperty('user_id');

			$User = $this->UserCollection->get($user_id);
			if(!$User) $User = User::find($user_id);

			$this->UserCollection->collectModel( $User, $user_id );
			$Comment->setProperty('username', $User->getUsername());
            $Comment->setProperty('profile_picture', $User->getProfilePicture());
		}
	}

	public function getCommentUser($user_id)
    {
        return $this->UserCollection->get($user_id);
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
