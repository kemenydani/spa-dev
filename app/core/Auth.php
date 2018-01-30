<?php
namespace core;

use core\Session as Session;
use core\Cookie as Cookie;

use models\User as User;

class Auth
{
    public static function user()
    {
        if( Session::exists('userId') )
        {
            return User::find( Session::get('userId') );
        }
        else if(Cookie::exists('user'))
        {
            $User = User::find(Cookie::get('user'), 'remember_token');

            if($User)
            {
                $User->login($User->getPassword(), true);
            }
            return $User;
        }
        return null;
    }

}
