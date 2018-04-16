<?php

namespace models;

use \core\Model as Model;

use models\Squad as Squad;
use models\Category as Category;
use models\MatchMap as MatchMap;
use models\EnemyTeam as EnemyTeam;

class Match extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'match';
    public static $COLUMNS = [
        'id',
        'squad_id',
        'enemy_team_id',
        'event_name',
        'game_id',
        'featured'
    ];

    public function getGame()
    {
        return Category::getGame($this->getGameId());
    }

    public function getSquad()
    {
        return Squad::find($this->getSquadId(), 'id');
    }

    public function getEnemyTeam()
    {
        return EnemyTeam::find($this->getEnemyTeamId(), 'id');
    }

    /**
     * @return MatchMap[]
     */
    public function getMaps() : array
    {
        return MatchMap::findAll($this->getId(), 'match_id');
    }

    /**
     * @return int
     */
    public function getTotalEnemyScore() : int
    {
        $maps = $this->getMaps();
        $score = 0;
        if($maps) foreach($maps as $map) $score += $map->getScoreEnemy();
        return $score;
    }

    /**
     * @return int
     */
    public function getTotalHomeScore() : int
    {
        $maps = $this->getMaps();
        $score = 0;
        if($maps) foreach($maps as $map) $score += $map->getScoreHome();
        return $score;
    }

    /**
     * @return array
     */
    public function getTotalScore() : array
    {
        return [
            'home' => $this->getTotalHomeScore(),
            'enemy' => $this->getTotalEnemyScore(),
        ];
    }

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getGameId()
    {
        return $this->getProperty('game_id');
    }

    public function getSquadId()
    {
        return $this->getProperty('squad_id');
    }

    public function getEnemyTeamId()
    {
        return $this->getProperty('enemy_team_id');
    }

    public function getEventName()
    {
        return $this->getProperty('enemy_team_id');
    }

    public function getFeatured()
    {
        return $this->getProperty('featured');
    }

}