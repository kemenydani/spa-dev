<?php

namespace models;

use \core\Model as Model;

class EnemyTeam extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'enemy_team';
    public static $COLUMNS = [
        'id',
        'name',
        'logo',
        'game_id',
    ];

    const IMAGE_PATH = __UPLOADS__ . '/images/enemy_team';

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getLogo()
    {
        return $this->getProperty('logo');
    }

    public function formatLogo()
    {
        return self::IMAGE_PATH . DIRECTORY_SEPARATOR . $this->getLogo();
    }

    public function getGameId()
    {
        return $this->getProperty('game_id');
    }

}
