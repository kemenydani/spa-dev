<?php

namespace models;

use models\ProductImage;
use models\ProductImageCollection;
use core\DB;

class Product extends \core\Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'product';
	
	public static $COLUMNS = [
		'id',
		'name',
		'price',
        'currency',
		'active',
        'in_stock',
        'desc'
	];
	
	const IMAGE_PATH = __UPLOADS__ . '/images/product';

	public function getId()
	{
		return $this->getProperty('id');
	}
	
	public function getName()
	{
		return $this->getProperty('name');
	}
	
	public function getPrice()
	{
		return $this->getProperty('price');
	}
	
	public function getActive()
	{
		return $this->getProperty('active');
	}

    public function getCurrency()
    {
        return $this->getProperty('currency');
    }

    public function getDesc()
    {
        return $this->getProperty('desc');
    }

    public function getInStock()
    {
        return $this->getProperty('in_stock');
    }

    public function hasAmountInStock($amount){
	    return ( (int)$this->getInStock() - (int)$amount ) >= 0;
    }

    public function isAvailable(){
        return (int)$this->getInStock() > 0;
    }

    public function getImageCollection()
    {
	    return new ProductImageCollection($this->getImages());
    }

	public function getImages()
	{
		return ProductImage::findAll($this->getId(), 'product_id');
	}
    public function getPreviewImage()
    {
        $images = $this->getImages();

        /* @var \models\ProductImage $image */

        foreach($images as $image) if($image->isPreviewImage()) return $image;

        if(count($images)) return $images[0];

        return null;
    }

    //TODO:: create commentable trait
    public function getComments()
    {
        $id = $this->getId();

        $stmt = " SELECT c.*, c.id as id, u.username, u.profile_picture FROM _xyz_product_comment_pivot pcp " .
                " LEFT JOIN _xyz_comment c ON c.id = pcp.comment_id " .
                " LEFT JOIN _xyz_user u ON u.id = c.user_id " .
                " WHERE pcp.product_id = $id "
        ;

        $sql = DB::instance()->query( $stmt );

        $rows = $sql->fetchAll(\PDO::FETCH_UNIQUE|\PDO::FETCH_ASSOC );

        $result = CommentCollection::toTree( $rows  );

        return $result;
    }

    //TODO:: create commentable trait
    public function addComment( Comment $Comment )
    {
        DB::instance()->beginTransaction();

        $Comment->save();

        $success = DB::pivot('product_comment_pivot', [
            'product_id' => $this->getProperty('id'),
            'comment_id' => $Comment->getProperty('id')
        ]);

        $success ? DB::instance()->commit() : DB::instance()->rollBack();

        return $Comment;
    }

	
}
