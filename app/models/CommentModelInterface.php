<?php

namespace models;

interface CommentModelInterface
{
	/**
	 * @param User $user
	 * @return mixed
	 */
	public function setUser( User $user );
}