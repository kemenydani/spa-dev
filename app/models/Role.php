<?php

namespace models;

use core\Model as Model;
use core\DB as DB;

class Role extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'season';

    public static $_PROPS = ['id', 'name'];
    public static $_PROPS_PROTECTED = [];

    public function getPermissions()
    {
        /*
        $stmt = " SELECT * FROM " . DB::_PREFIX_ . "role_permission_pivot rpp" .
                " LEFT JOIN " . DB::_PREFIX_ . "permission p" .
                " ON " .
                " WHERE role_id = " . $this->getProperty(self::$_UNIQUE_KEY);
         */
        //$sql = DB::instance()->query();
    }

}