<?php

namespace models;

use core\Model as Model;

class Comment extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'comment';

    public static $_PROPS = ['id'];
    public static $_PROPS_PROTECTED = [];


}