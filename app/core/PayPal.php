<?php

namespace core;

use models\Product;

class PayPal
{
    static $businessEmail = 'business.webdevplace@gmail.com';
    static $successRoute = 'http://phpapp/payment/paypalPaymentSuccesful';
    static $cancelRoute  = 'http://phpapp/payment/paypalPaymentCancelled';
    static $notifyRoute  = 'http://phpapp/payment/paypalPaymentResponse';
    static $currency = 'EUR';

    public static function buildQueryString( array $orderDetails )
    {
        $querystring = '';

        // Firstly Append paypal account to querystring
        $querystring .= "?business=".urlencode(static::$businessEmail)."&";

        // Append amount& currency (£) to quersytring so it cannot be edited in html

        //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
        $querystring .= "item_name=".urlencode('foo')."&";
        $querystring .= "amount=".urlencode($orderDetails['amount'])."&";
         $querystring .= "quantity=".urlencode($orderDetails['quantity'])."&";
        //$querystring .= "address_override=1&";
        //loop for posted values and append to querystring
        foreach($orderDetails as $key => $value){
            $value = urlencode(stripslashes($value));
            $querystring .= "$key=$value&";
        }

        // Append paypal return addresses
        $querystring .= "rm=2&";
        $querystring .= "return=".urlencode(stripslashes(static::$successRoute))."&";
        $querystring .= "cancel_return=".urlencode(stripslashes(static::$cancelRoute))."&";
        $querystring .= "notify_url=".urlencode(static::$notifyRoute);

        // Append querystring with custom field
        //$querystring .= "&custom=".USERID;

        // Redirect to paypal IPN

        return $querystring;
    }

}