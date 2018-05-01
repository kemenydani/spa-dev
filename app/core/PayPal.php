<?php

namespace core;

use models\Payment as Payment;

class PayPal
{
    static $businessEmail = 'business.webdevplace@gmail.com';
    static $successRoute  = 'http://dev1.webdevplace.com/paypal/paymentsuccess';
    static $cancelRoute   = 'http://dev1.webdevplace.com/paypal/paymentCancelled';
    static $notifyRoute   = 'http://dev1.webdevplace.com/paypal/ipnlistener';

    public static function generateUrl( array $formData, Payment $Payment )
    {
	    $url = '';
	
	    // Firstly Append paypal account to querystring
	    $url .= "?business=".urlencode(self::$businessEmail)."&";
	
	    //loop for posted values and append to querystring
	    foreach($formData as $key => $value)
	    {
		    $value = urlencode(stripslashes($value));
		    $url .= "$key=$value&";
	    }
	
	    $url .= "custom=".urlencode(session_id() . ':' . $Payment->getId());
	    
	    // Append paypal return addresses
	    $url .= "rm=2&";
	    //$url .= "return=".urlencode(stripslashes(self::$successRoute))."&";
	    $url .= "cancel_return=".urlencode(stripslashes(self::$cancelRoute))."&";
	    $url .= "notify_url=".urlencode(self::$notifyRoute);
	    
        return 'https://www.sandbox.paypal.com/cgi-bin/webscr'.$url;
    }
}
