<?php

namespace models;

use core\Model as Model;
use core\Session as Session;
use core\Cookie as Cookie;
use core\Hash as Hash;

class User extends Model {

    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'user';

    public static $_PROPS = ['id', 'username', 'password', 'remember_token', 'email', 'profile_picture', 'country_code', 'date_created', 'date_updated'];
    public static $_PROPS_PROTECTED = ['password', 'remember_token'];
    public static $_PROPS_SEARCHABLE = ['id', 'username', 'email', 'country_code', 'date_created'];

    const MEDIUM_DATASET = ['id', 'username', 'profile_picture'];
    const IMAGE_PATH = __UPLOADS__ . '/images/user';

    public function getId(){
        return $this->getProperty('id');
    }

    public function getUserName(){
        return $this->getProperty('username');
    }

    public function getEmail(){
        return $this->getProperty('email');
    }

    public function getProfilePicture()
    {
        return 'user_picture/' . $this->getProperty('profile_picture');
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
