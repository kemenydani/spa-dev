<?php

namespace models;

use core\Model as Model;
use core\Session as Session;
use core\Cookie as Cookie;
use core\Hash as Hash;

class User extends Model {

    public static $primaryKey = 'id';
    public static $table = 'user';
    public static $columns = ['id', 'username', 'password', 'remember_token', 'email', 'profile_picture', 'country_code', 'date_created', 'date_updated'];

    const PUBLIC_DATASET = [ 'id', 'username', 'profile_picture', 'email', 'profile_picture', 'country_code', 'date_created', 'date_updated' ];

    const IMAGE_PATH = __UPLOADS__ . '/images/user';

    const FIELD_RULES = [
        'username' => 'required|unique|max:20',
        'email' => 'required|unique|filter:email',
        'password' => 'required'
    ];

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
