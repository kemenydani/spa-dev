<?php

use core\DB as DB;

if(getConfig('remote', false))
{
	DB::$_PREFIX_   = getConfig('_REMOTE_DB_PREFIX_');
	DB::$_HOST_     = getConfig('_REMOTE_DB_HOST_');
	DB::$_DB_NAME_  = getConfig('_REMOTE_DB_NAME_');
	DB::$_USERNAME_ = getConfig('_REMOTE_DB_USER_');
	DB::$_PASSWORD_ = getConfig('_REMOTE_DB_PW_');
}
else
{
	DB::$_PREFIX_   = getConfig('_LOCALE_DB_PREFIX_');
	DB::$_HOST_     = getConfig('_LOCALE_DB_HOST_');
	DB::$_DB_NAME_  = getConfig('_LOCALE_DB_NAME_');
	DB::$_USERNAME_  = getConfig('_LOCALE_DB_USER_');
	DB::$_PASSWORD_ = getConfig('_LOCALE_DB_PW_');
}