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
		'file_name',
    ];
	
	const IMAGE_PATH = __UPLOADS__ . '/images/gallery';
    
    public function getGalleryId()
    {
        return $this->getProperty('gallery_id');
    }

    public function getGallery()
    {
        return Gallery::find($this->getGalleryId());
    }
	
	public function getFileName()
	{
		return $this->getProperty('file_name');
	}

    public function requestImageUrl()
    {
        return '/requestGalleryImage/' . $this->getFileName();
    }
	
	public function deleteImage()
	{
	
	}
    
}
