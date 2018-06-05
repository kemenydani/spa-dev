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
        'country_name',
        'date_created',
        'date_updated',
	    'password_change_secret',
	    'password_temporary'
    ];

    const PUBLIC_DATASET = [ 'id', 'username', 'country_name', 'profile_picture', 'email', 'profile_picture', 'country_code', 'date_created', 'date_updated' ];
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

    public function getPasswordChangeSecret()
    {
	    return $this->getProperty('password_change_secret');
    }
	
	public function getPasswordTemporary()
	{
		return $this->getProperty('password_temporary');
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

    public function getCountry()
    {
        return new Country($this->getCountryCode());
    }

    public function formatProfilePicture()
    {
        return '/userProfilePicture/' . $this->getProfilePicture();
    }
    
	public function requestProfilePicture()
	{
		return '/userProfilePicture/' . $this->getProfilePicture();
	}

	public function getUserProfile()
    {
        return UserProfile::find($this->getId(), 'user_id');
    }

	public function getLastComments( $limit = 3 )
    {
        $q = "SELECT * FROM _xyz_comment WHERE user_id = ? ORDER BY id DESC LIMIT {$limit} ";
        $c = CommentCollection::queryToCollection($q, $this->getId());
        $r = [];
        if($c) $r = $c->getProperties();
        return $r;
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

    public function login($password = null, $remember = false)
    {
        if($password)
        {
            if(password_verify($password, $this->getProperty('password')))
            {
                if($remember)
                {
                    $remember_token = Hash::unique();

                    if(!$this->getProperty('remember_token'))
                    {
                        $this->setProperty('remember_token', $remember_token);
                        $this->save();
                    }
                    else
                    {
                        $remember_token = $this->getProperty('remember_token');
                    }
                    Cookie::put('user', $remember_token, 604800);
                }
                Session::put('userId', $this->getProperty('id'));
                return true;
            } else {
                // invalid password
            }
        }
        return false;
    }

    public static function verify_password($password, $prediction)
    {
        //return password_verify($prediction, $password);
    }

}
