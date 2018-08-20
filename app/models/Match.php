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
        'event_id',
        'game_id',
        'featured',
        'date_played'
    ];

    public function getGame()
    {
        return Category::getGame($this->getGameId());
    }

    public function getSquad()
    {
        return Squad::find($this->getSquadId());
    }

    public function getEnemyTeam()
    {
        return EnemyTeam::find($this->getEnemyTeamId());
    }

    public function getEvent()
    {
	    return Event::find($this->getEventId());
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
        if($maps) foreach($maps as $map) $score += (int)$map->getScoreEnemy();
        return $score;
    }

    public function formatTotalEnemyScore() : int
    {
        $maps = $this->getMaps();
        $score = 0;
        if($maps) foreach($maps as $map) $score += (int)$map->formatScoreEnemy();
        return $score;
    }

    /**
     * @return int
     */
    public function getTotalHomeScore() : int
    {
        $maps = $this->getMaps();
        $score = 0;
        if($maps) foreach($maps as $map) $score += (int)$map->getScoreHome();
        return $score;
    }

    public function formatTotalHomeScore() : int
    {
        $maps = $this->getMaps();
        $score = 0;
        if($maps) foreach($maps as $map) $score += (int)$map->formatScoreHome();
        return $score;
    }

    /**
     * @param int $score1
     * @param int $score2
     */

    /**
     * @return array
     */
    public function getTotalScore( $real = false ) : array
    {
        return [
            'home' => $this->getTotalHomeScore(),
            'enemy' => $this->getTotalEnemyScore(),
        ];
    }

    public function getDatePlayed()
    {
        return $this->getProperty('date_played');
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

    public function getEventId()
    {
        return $this->getProperty('enemy_team_id');
    }

    public function getFeatured()
    {
        return $this->getProperty('featured');
    }

}