<?php

namespace models;

class Payment extends \core\Model
{
	public static $PKEY = 'id';
	public static $TABLE = 'payment';
	
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

	public function getId()
	{
		return $this->getProperty('id');
	}
	
	public function getPost()
	{
		return $this->getProperty('post');
	}
 
	
}