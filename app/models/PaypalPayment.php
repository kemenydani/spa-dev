<?php

namespace models;

class PaypalPayment extends \core\Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'paypal';
	
	public static $COLUMNS = [
		'id',
		'product_id',
		'payment_status',
		'pending_reason',
		'product_name',
		'quantity',
		'gross',
		'amount',
		'currency',
		'payer_id',
		'payer_email',
		'session_id',
		'txn_id',
		'ipn_track_id',
		'date_checkout',
		'last_updated',
		'post'
	];
	
	static $SEARCH_COLUMNS = ['product_name', 'amount', 'currency', 'txn_id', 'ipn_track_id', 'payer_email', 'payment_status'];
	
	public function getId()
	{
		return $this->getProperty('id');
	}

    public function getSessionId()
    {
        return $this->getProperty('session_id');
    }

    public function getPost()
	{
		return $this->getProperty('post');
	}
 
	
}