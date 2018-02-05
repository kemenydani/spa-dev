<?php

namespace models;

use core\Model as Model;

class Permission extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'permission';

    public static $_PROPS = ['id', 'name'];
    public static $_PROPS_PROTECTED = [];


}