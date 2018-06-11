<?php

namespace core;

class Config
{
    protected $settings = null;
    protected static  $_instance = null;

    public function __construct()
    {
        $this->settings = DB::instance()->getAll('SELECT * FROM `_xyz_setting`');


        $stmt = "SELECT * FROM `_xyz_setting`";

        $sql = DB::instance()->query( $stmt );

        $rows = $sql->fetchAll(\PDO::FETCH_ASSOC );

        $res = [];

        foreach($rows as $i => $row) $res[$row['codename']] = $row;

        $this->settings = $res;
    }

    public static function instance()
    {
        if ( self::$_instance === null )
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function get($codename, $default = null)
    {
        if(array_key_exists($codename, $this->settings)) return $this->settings[$codename]['val'];
        if($default) return $default;
        return getConfig($codename);
    }

    public function getSocialLinks()
    {
        $res = [];

        foreach($this->settings as $key => $setting)
        {
            if((strpos($key, 'social_') === 0))
            {
                $nk = str_replace('social_', '', $key);
                if($this->settings['show_social_' . $nk]['val'] == 1) $res[$nk] = $setting['val'];
            }
        }

        return $res;
    }

}
