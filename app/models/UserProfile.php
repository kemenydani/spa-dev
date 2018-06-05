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
        'show_bio',
		'bio',
        'show_gear',
        'show_social',
        'show_comments',
        'social_facebook',
        'social_twitter',
        'social_twitch',
        'social_youtube',
        'gear_mouse',
        'gear_keyboard',
        'gear_headset',
        'gear_vga',
        'gear_processor',
        'gear_chair',
    ];

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getUserId()
    {
        return $this->getProperty('user_id');
    }

    public function formatSocialJson()
    {

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
