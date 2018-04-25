<?php

namespace models;
use \core\Model as Model;

class Link extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'link';
    public static $COLUMNS = [
        'id',
        'name',
        'url',
        'blank',
        'context',
        'fa_class',
    ];

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getUrl()
    {
        return $this->getProperty('url');
    }

    public function getBlank()
    {
        return $this->getProperty('blank');
    }

    public function getContext()
    {
        return $this->getProperty('context');
    }

    public function getFaClass()
    {
        return $this->getProperty('fa_class');
    }

    //

}