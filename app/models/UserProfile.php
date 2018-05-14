<?php

namespace models;

use \models\User;
use \core\Model;

class UserProfile extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'user_profile';
    public static $COLUMNS = [
        'id',
        'user_id',
		'bio',
        'facebook_url',
        'youtube_url',
        'twitch_url',
        'instagram_url',
    ];

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getUserId()
    {
        return $this->getProperty('user_id');
    }

    public function getBio()
    {
        return $this->getProperty('bio');
    }

    public function getFacebookUrl()
    {
        return $this->getProperty('facebook_url');
    }

    public function getYoutubeUrl()
    {
        return $this->getProperty('youtube_url');
    }

    public function getInstagramUrl()
    {
        return $this->getProperty('instagram_url');
    }

    public function getTwitchUrl()
    {
        return $this->getProperty('twitch_url');
    }
    
}
