<?php

namespace models;

use \models\Image as Image;

class ProductImage extends Image
{
    public static $PKEY = 'id';
    public static $TABLE = 'product_image';
    public static $COLUMNS = [
        'id',
        'product_id',
		'file_name',
    ];
	
	const IMAGE_PATH = __UPLOADS__ . '/images/product';
    
    public function getProductId()
    {
        return $this->getProperty('product_id');
    }

    public function getProduct()
    {
        return Product::find($this->getProductId());
    }
	
	public function getFileName()
	{
		return $this->getProperty('file_name');
	}

    public function requestImageUrl()
    {
        return '/requestProductImage/' . $this->getFileName();
    }
	
	public function deleteImage()
	{
	
	}
    
}
