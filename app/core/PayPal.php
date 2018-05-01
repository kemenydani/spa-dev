<?php

namespace core;

class PayPal
{
    static $businessEmail = 'business.webdevplace@gmail.com';
    static $successRoute  = 'http://dev1.webdevplace.com/paypal/paymentResponse';
    static $cancelRoute   = 'http://dev1.webdevplace.com/paypal/paymentCancelled';
    static $notifyRoute   = 'http://dev1.webdevplace.com/paypal/paymentResponse';

    public static function generateUrl( array $formData )
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
	
	    // Append paypal return addresses
	    $url .= "rm=2&";
	    $url .= "return=".urlencode(stripslashes(self::$successRoute))."&";
	    $url .= "cancel_return=".urlencode(stripslashes(self::$cancelRoute))."&";
	    $url .= "notify_url=".urlencode(self::$notifyRoute);

        return 'https://www.sandbox.paypal.com/cgi-bin/webscr'.$url;
    }
}
