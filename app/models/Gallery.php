<?php

namespace models;

use \core\Model as Model;

class Gallery extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'gallery';

    public static $_PROPS = ['id', 'title'];
    public static $_PROPS_PROTECTED = [];

}