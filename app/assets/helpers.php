<?php

function debug( $var)
{
    var_dump( $var );
    die();
}

function getConfig( $name, $default = null )
{
	echo __APPDIR__ . '/config.ini';
	$file = parse_ini_file(__APPDIR__ . '/config.ini',false );
	if(is_array($file) && array_key_exists($name, $file)) return $file[$name];
	return $default;
}

function toBool($var) {
    if (!is_string($var)) return (bool) $var;
    switch (strtolower($var)) {
        case '1':
        case 'true':
        case 'on':
        case 'yes':
        case 'y':
            return true;
        default:
            return false;
    }
}

function get_post_max_size_bytes( $size_str = null )
{
    if( $size_str === null ) $size_str = ini_get('post_max_size');

    switch (substr ($size_str, -1))
    {
        case 'M': case 'm': return (int)$size_str * 1048576;
        case 'K': case 'k': return (int)$size_str * 1024;
        case 'G': case 'g': return (int)$size_str * 1073741824;
        default: return $size_str;
    }
}

