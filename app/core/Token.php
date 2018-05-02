<?php

namespace core;

class Token
{
    public static function validate($token)
    {
        return !hash_equals(Session::get('token'), $token);
    }

    public static function exists()
    {
        return Session::exists('token');
    }

    public static function make()
    {
        return Session::put('token', bin2hex(random_bytes(32)));
    }

    public static function get()
    {
        if(self::exists()) return Session::get('token');
        return self::make();
    }
}