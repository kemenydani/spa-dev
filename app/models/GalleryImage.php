<?php

namespace models;

use \models\Image as Image;

class GalleryImage extends Image
{
    public static $PKEY = 'id';
    public static $TABLE = 'gallery_image';
    public static $COLUMNS = [
        'id',
        'gallery_id',
        'folder',
        'filename'
    ];

    public function getGalleryId()
    {
        return $this->getProperty('gallery_id');
    }

    public function getGallery()
    {
        return Gallery::find($this->getGalleryId());
    }

    public function deleteImage()
    {

    }

}
