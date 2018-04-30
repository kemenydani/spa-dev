<?php

namespace core;

use models\Product;

class PayPal
{
    static $businessEmail = 'business.webdevplace@gmail.com';
    static $successRoute = 'http://dev1.webdevplace.com/payment/paypalPaymentSuccesful';
    static $cancelRoute  = 'http://dev1.webdevplace.com/payment/paypalPaymentCancelled';
    static $notifyRoute  = 'http://dev1.webdevplace.com/payment/paypalPaymentResponse';
    static $currency = 'EUR';

    public static function buildQueryString( array $orderDetails )
    {
	    $querystring = '';
	
	    // Firstly Append paypal account to querystring
	    $querystring .= "?business=".urlencode(self::$businessEmail)."&";
	
	    // Append amount& currency (£) to quersytring so it cannot be edited in html
	
	    //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	    $querystring .= "item_name=".urlencode($orderDetails['item_name'])."&";
	    $querystring .= "amount=".urlencode($orderDetails['item_amount'])."&";
	
	    //loop for posted values and append to querystring
	    foreach($orderDetails as $key => $value){
		    $value = urlencode(stripslashes($value));
		    $querystring .= "$key=$value&";
	    }
	
	    // Append paypal return addresses
	    $querystring .= "return=".urlencode(stripslashes(self::$successRoute))."&";
	    $querystring .= "cancel_return=".urlencode(stripslashes(self::$cancelRoute))."&";
	    $querystring .= "notify_url=".urlencode(self::$notifyRoute);

        return $querystring;
    }

}