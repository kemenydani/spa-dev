<?php

namespace models;

use \core\Model as Model;
use \models\SquadMember as SquadMember;

class Squad extends Model
{
	const IMAGE_PATH = __UPLOADS__ . '/images/squad';
	
	public static $PKEY = 'id';
	public static $TABLE = 'squad';
	
	public static $COLUMNS = [
		'id',
		'name',
		'game_id',
		'header_image',
		'position',
	];

	public function getMembers()
	{
		return SquadMember::findAll($this->getId(), 'squad_id');
	}
	
	public function getMember($member_id)
	{
		return SquadMember::getOne([
			'squad_id' => $this->getId(),
			'id' => $member_id
		]);
	}
	
	public function getId()
	{
		return $this->getProperty('id');
	}
	
}
