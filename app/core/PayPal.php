<?php

namespace core;

use core\PaypalIPN as PaypalIPN;

class PayPal extends PaypalIPN
{
    static $successRoute;
    static $cancelRoute;

    public static function generateUrl( array $formData, $payment_id )
    {
	    $url = "?business=".urlencode(getConfig('paypal_business_email'))."&";

	    foreach($formData as $key => $value)
	    {
		    $value = urlencode(stripslashes($value));
		    $url .= "$key=$value&";
	    }
	
	    $url .= "custom=".urlencode(session_id() . ':' . $payment_id);
	    $url .= "return=".urlencode(stripslashes(self::$successRoute))."&";
	    $url .= "cancel_return=".urlencode(stripslashes(self::$cancelRoute))."&";
	    
        return 'https://www.sandbox.paypal.com/cgi-bin/webscr'.$url;
    }

    public static function validateCheckoutForm( array $formData )
    {

    }

    public static function paymentFailed( $status )
    {

    }

}
