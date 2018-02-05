<?php

namespace models;

use \core\Model as Model;

class Stream extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'stream';

    public static $_PROPS = ['id', 'title', 'category_id'];
    public static $_PROPS_PROTECTED = [];
}