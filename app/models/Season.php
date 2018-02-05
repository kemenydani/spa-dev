<?php

namespace models;

use core\Model as Model;

class Season extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'season';

    public static $_PROPS = ['id', 'title'];
    public static $_PROPS_PROTECTED = [];


}