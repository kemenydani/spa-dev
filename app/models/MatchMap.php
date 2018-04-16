<?php

namespace models;

use \core\Model as Model;

class MatchMap extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'match_map';
    public static $COLUMNS = [
        'id',
        'match_id',
        'name',
        'score_home',
        'score_enemy',
    ];

    public function isWin()
    {
        if(is_numeric($this->getScoreHome()) && is_numeric($this->getScoreEnemy()))
        {
            return (int)$this->getScoreHome() > (int)$this->getScoreEnemy();
        }
        return false;
    }

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getMatchId()
    {
        return $this->getProperty('match_id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getScoreHome()
    {
        return $this->getProperty('score_home');
    }

    public function getScoreEnemy() : int
    {
        return (int)$this->getProperty('score_enemy');
    }

}