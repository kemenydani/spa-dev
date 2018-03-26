<?php

function debug( $var)
{
    var_dump( $var );
    die();
}

function array_reverse_recursive($arr) {
    foreach ($arr as $key => $val) {
        if (is_array($val))
            $arr[$key] = array_reverse_recursive($val);
    }
    return array_reverse($arr);
}

function getConfig( $name, $default = null )
{
	$files = array_diff(scandir(__APPDIR__ . '/config'), array('.', '..'));

	$configs = [];
	
	foreach($files as $file)
	{
		$content = parse_ini_file(__APPDIR__ . '/config/' . $file,false );
		if(is_array($content)) $configs = array_merge($content, $configs);
	}
	
	if(is_array($configs) && array_key_exists($name, $configs)) return $configs[$name];
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

