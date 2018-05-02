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

    public static function paymentFailed( $payment_status )
    {
        $backStockReason = ['denied','expired','failed','voided'];

        return in_array($payment_status, $backStockReason);
    }

}

/*
 * https://github.com/paypal/ipn-code-samples/blob/master/php/example_usage_advanced.php
 * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
 */

/*
       {"mc_gross":"30.00",
           "protection_eligibility":"Eligible",
           "address_status":"confirmed",
           "payer_id":"TS36AQYH7LMRQ",
           "address_street":"ESpachstr. 1",
           "payment_date":"09:55:34 May 01, 2018 PDT",
           "payment_status":"Pending",
           "charset":"windows-1252",
           "address_zip":"79111",
           "first_name":"John",
           "address_country_code":"DE",
           "address_name":"John Doe",
           "notify_version":"3.9",
           "custom":"0df2aafdd243e1e7f7b68d9674f99158:30rm=2",
           "payer_status":"verified",
           "business":"business.webdevplace@gmail.com",
           "address_country":"Germany",
           "address_city":"Freiburg",
           "quantity":"3",
           "verify_sign":"Ae5eSJQKcmMlRPleNRcieWxhxTQbAvugJvT2rWVb4zC78P0iRT1YKMB0",
           "payer_email":"buyer.webdevplace@gmail.com",
           "txn_id":"6W897866UG3312931",
           "payment_type":"instant",
           "last_name":"Doe",
           "address_state":"Empty",
           "receiver_email":"business.webdevplace@gmail.com",
           "receiver_id":"P72KH2WK99UP4",
           "pending_reason":"multi_currency",
           "txn_type":"web_accept",
           "item_name":"testproduct",
           "mc_currency":"GBP",
           "item_number":"1",
           "residence_country":"DE",
           "test_ipn":"1",
           "transaction_subject":"",
           "payment_gross":"",
           "ipn_track_id":"451a3a722cbeb"
       }
       */