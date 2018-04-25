<?php

namespace models;
use \core\Model as Model;

class Partner extends Model
{
    const IMAGE_PATH = __UPLOADS__ . '/images/partner';

    public static $PKEY = 'id';
    public static $TABLE = 'partner';
    public static $COLUMNS = [
        'id',
        'name',
        'logo',
        'website_url',
        'description'
    ];

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getLogo()
    {
        return $this->getProperty('logo');
    }

    public function getWebsiteUrl()
    {
        return $this->getProperty('website_url');
    }

    public function getDescription()
    {
        return $this->getProperty('description');
    }

    //

    public function requestLogo()
    {
        return '/requestPartnerLogo/' . $this->getLogo();
    }

}