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

    public function getImageCollection()
    {
        return new GalleryImageCollection($this->getImages());
    }
    
    public function getFeaturedImage()
    {
    	/* @var GalleryImageCollection $ic */
    	$ic = $this->getImages();
    	
    	if(!$ic) return null;
    	
    	/* @var \models\GalleryImage $GalleryImage; */
	
	    $FeaturedGalleryImage = null;
    	
    	foreach($ic as $key => $GalleryImage)
    	{
    		if(!$GalleryImage->getProperty('featured')) continue;
		    $FeaturedGalleryImage = $GalleryImage;
	    }
	    
	    if($FeaturedGalleryImage) return $FeaturedGalleryImage;
	    
    	$randKey = array_rand($ic, 1);
    	
    	return $ic[$randKey];
    }

}