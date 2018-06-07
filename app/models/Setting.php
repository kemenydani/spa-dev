<?php

namespace models;

use core\Model;

class Setting extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'setting';
    public static $COLUMNS = [
        'id',
        'codename',
        'protected',
        'val',
        'date_modified',
    ];

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getCodename()
    {
        return $this->getProperty('codename');
    }

    public function getProtected()
    {
        return $this->getProperty('protected');
    }

    public function getVal()
    {
        return $this->getProperty('val');
    }

    public function getDateModified()
    {
        return $this->getProperty('date_modified');
    }

}