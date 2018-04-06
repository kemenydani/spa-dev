<?php

namespace models;

interface CommentCollectionInterface
{
	/**
	 * @return Comment
	 */
	public function all() : Comment;
}