<?php

namespace models;

class Product extends \core\Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'product';
	
	public static $COLUMNS = [
		'id',
		'name',
		'price',
		'active',
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
	
}