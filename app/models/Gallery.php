<?php

namespace models;

use \core\Model as Model;

use models\GalleryImage as GalleryImage;

class Gallery extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'gallery';
    public static $COLUMNS = [
        'id',
        'name',
        'folder',
    ];
 
    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getFolder()
    {
        return $this->getProperty('folder');
    }

    public function getImages()
    {
        return GalleryImage::findAll($this->getId(), 'gallery_id');
    }

}