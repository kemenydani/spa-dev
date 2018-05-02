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

	
}