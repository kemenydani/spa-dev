<?php

namespace models;

use core\Model as Model;
use core\Session as Session;
use core\Cookie as Cookie;
use core\Hash as Hash;

class User extends Model {

    public static $PKEY = 'id';
    public static $TABLE = 'user';
    public static $COLUMNS = [
        'id',
        'username',
        'password',
        'remember_token',
        'email',
        'profile_picture',
        'country_code',
        'date_created',
        'date_updated'
    ];

    const PUBLIC_DATASET = [ 'id', 'username', 'profile_picture', 'email', 'profile_picture', 'country_code', 'date_created', 'date_updated' ];
    const SMALL_DATASET = [ 'id', 'username', 'profile_picture', 'email'];
    const IMAGE_PATH = __UPLOADS__ . '/images/user';
    const NO_USER_IMAGE = 'no-user-image.png';

    const FIELD_RULES = [
        'username' => 'required|unique|max:20',
        'email' => 'required|unique|filter:email',
        'password' => 'required'
    ];

    public function getUsername()
    {
        return $this->getProperty('username');
    }

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getEmail()
    {
        return $this->getProperty('email');
    }

    public function getCountryCode()
    {
        return $this->getProperty('country_code');
    }

    public function getPassword()
    {
        return $this->getProperty('password');
    }

    public function getRememberToken()
    {
        return $this->getProperty('remember_token');
    }

    public function getProfilePicture()
    {
        return $this->getProperty('profile_picture');
    }

    public function formatProfilePicture()
    {
        return '/userProfilePicture/' . $this->getProfilePicture();
    }
    
	public function requestProfilePicture()
	{
		return '/userProfilePicture/' . $this->getProfilePicture();
	}

	public static function getProfilePictureUrl($id)
    {
        return'/userProfilePicture/' . $id;
    }

    public function logout()
    {
        Session::delete('userId');
        Cookie::delete('user');
    }

    public function login($password, $remember = false)
    {
        if($password)
        {
            // TODO: do proper validation
            if($this->getPassword() === $password)
            {
                if($remember)
                {
                    $remember_token = Hash::unique();

                    if(!$this->getRememberToken())
                    {
                        $this->setRememberToken($remember_token);
                        $this->save();
                    }
                    else
                    {
                        $remember_token = $this->getRememberToken();
                    }
                    Cookie::put('user', $remember_token, 604800);
                }
                Session::put('userId', $this->getId());
                return true;
            }
        }
        return false;
    }

    public static function verify_password($password, $prediction)
    {
        //return password_verify($prediction, $password);
    }

}
