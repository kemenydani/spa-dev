<?php

namespace models;

use \core\Model as Model;

class Member extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'member';

    public static $_PROPS = ['id', 'user_id'];
    public static $_PROPS_PROTECTED = [];

}