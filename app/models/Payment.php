<?php

namespace models;

class Payment extends \core\Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'payment';
	
	public static $COLUMNS = [
		'id',
		'product_id',
		'product_name',
		'amount',
		'quantity',
		'txnid',
		'payer_id',
		'date_checkout',
		'date_confirmed',
		'date_accepted',
		'date_rejected',
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
	public function getPost()
	{
		return $this->getProperty('post');
	}
 
	
}