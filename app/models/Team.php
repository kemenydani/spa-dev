<?php

namespace models;

use \core\Model as Model;

class Team extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'team';

    public static $_PROPS = ['id', 'name', 'category_id'];
    public static $_PROPS_PROTECTED = [];



}