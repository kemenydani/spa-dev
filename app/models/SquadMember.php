<?php

namespace models;

use \core\Model as Model;

class SquadMember extends Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'squad_member';
	
	public static $COLUMNS = [
		'id',
		'name',
		'squad_id',
	];
}
