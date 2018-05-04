<?php

namespace models;

use core\Model as Model;

class Video extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'video';
    public static $COLUMNS = [
        'id',
        'title',
    ];

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

}
