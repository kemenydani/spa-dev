<?php

namespace models;

use \core\DB as DB;
use \core\Model;

class Setting extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'setting';

    public static $_PROPS = ['id', 'value'];
    public static $_PROPS_PROTECTED = [];


}