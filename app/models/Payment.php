<?php

namespace models;

class Payment extends \core\Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'payment';
	
	public static $COLUMNS = [
		'id',
        'product_id'
	];

	public function getId()
	{
		return $this->getProperty('id');
	}
	
	public function getTxnId()
	{
		return $this->getProperty('txnid');
	}
	
	public function getPaymentAmount()
	{
		return $this->getProperty('payment_status');
	}
	
	public function getItemId()
	{
		return $this->getProperty('item_id');
	}

    public function getCreatedTime()
    {
        return $this->getProperty('created_time');
    }
	
}