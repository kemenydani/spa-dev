<?php

namespace models;

use core\Model as Model;

class Tournament extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'tournament';

    public static $_PROPS = ['id', 'title', 'date_created'];
    public static $_PROPS_PROTECTED = [];

}
