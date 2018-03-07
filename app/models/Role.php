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
        $stmt = "SELECT * FROM " . DB::_PREFIX_ . "role_permission_pivot";

        $rows = DB::instance()->query( $stmt );

        $permissions = [];

        foreach( $rows as $row )
        {
            $permission = Permission::find( $row['permission_id'] );

            if( $permission ) $permissions[] = $permission;
        }

        return $permissions;
    }

}