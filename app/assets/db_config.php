<?php

use core\DB as DB;

DB::$_PREFIX_   = getConfig('_DB_PREFIX_');
DB::$_HOST_     = getConfig('_DB_HOST_');
DB::$_DATABASE_ = getConfig('_DB_DATABASE_');
DB::$_USERNAME_ = getConfig('_DB_USERNAME_');
DB::$_PASSWORD_ = getConfig('_DB_PASSWORD_');
