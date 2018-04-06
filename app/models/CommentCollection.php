<?php

namespace models;

use core\ModelCollection;
use models\UserCollection as UserCollection;
use models\User as User;

class CommentCollection extends ModelCollection
{
	/**
	 * @var UserCollection
	 */
	protected $UserCollection;
	
	public function __construct()
	{
		$this->collectDistinctUsers();
	}
	
	public function collectArray( array $comments )
	{
		foreach($comments as $key => $comment) $this->collect(Comment::create($comment), $key);
		return $this;
	}
	
	private function collectDistinctUsers() : UserCollection
	{
		$this->UserCollection = new UserCollection();
		
		foreach( $this->all() as $Comment )
		{
			$user_id = $Comment->getProperty('user_id');
			$User = $this->UserCollection->get($user_id);
			
			if($User) { $Comment->setUser($User); continue; }
			
			$User = User::find($user_id);
			$this->UserCollection->collect( $User );
		}
	}
	
	public function formatCommentTree()
	{
	
	}
	
	public function withUsers()
	{
		return $this;
	}
	
	public function users() : UserCollection
	{
		return $this->UserCollection;
	}
	
}
