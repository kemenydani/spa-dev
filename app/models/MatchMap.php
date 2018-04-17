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
        'show_real'
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

    public function getShowReal()
    {
        return (bool)$this->getProperty('show_real');
    }

    /**
     * @param int $score
     * @param int $scoreCompareTo
     * @return int
     */
    public static function toOneBitResult( int $score, int $scoreCompareTo ) : int
    {
        if($score > $scoreCompareTo) return 1;
        if($score < $scoreCompareTo) return 0;
        return 1;
    }

    public function getScoreHome() : int
    {
        return (int)$this->getProperty('score_home');
    }

    public function getScoreEnemy() : int
    {
        return (int)$this->getProperty('score_enemy');
    }

    public function formatScoreHome() : int
    {
        $scoreHome =  $this->getScoreHome();
        if($this->getShowReal() === false)
        {
            return self::toOneBitResult($scoreHome, $this->getScoreEnemy());
        }
        return $scoreHome;
    }

    public function formatScoreEnemy() : int
    {
        $scoreEnemy =  $this->getScoreEnemy();
        if($this->getShowReal() === false)
        {
            return self::toOneBitResult($scoreEnemy, $this->getScoreHome());
        }
        return $scoreEnemy;
    }

}