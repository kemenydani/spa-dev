<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\Product as Product;
use models\Payment as Payment;
use core\PayPal as Paypal;

use core\Session as Session;

class PaymentController extends ViewController
{
	/*
    private function validateOrder($parsedBody)
    {
        $errors = [];
        return [];
        if(!array_key_exists('token', $parsedBody)) return false;
        if(!hash_equals(Session::get('token'), $parsedBody['token'])) return false;

        $Product = Product::find($parsedBody['productId']);

        if(!$Product)                return false;
        if(!$Product->getActive())   return false;
        if(!$Product->isAvailable()) return false;

        $orderCurrencyCode = $parsedBody['currency_code'];

        if($Product->getCurrencyCode() !== $orderCurrencyCode) return false;

        $itemQuantity = (int)$parsedBody['quantity'];
        $orderProductPrice = (int)$parsedBody['quantity'];

        $orderTotal   = $itemQuantity * $orderProductPrice;
        $controlTotal = $itemQuantity * (float)$Product->getPrice();

        if($orderTotal !== $controlTotal) return false;

        if(!$Product->hasAmountInStock()) return false;

        return $errors;
    }

    public function postValidatePaypalPayment( Request $request, Response $response, $args )
    {
        $parsedBody = $request->getParsedBody();

        $errors = $this->validateOrder($parsedBody);

        return $response->withStatus(200)->withJson($errors);
    }
*/

    public function postPaypalPaymentRequest( Request $request, Response $response, $args)
    {
        $parsedBody = $request->getParsedBody();
        
        $parsedBody['amount'] = 10;

        echo PayPal::buildQueryString($parsedBody);

        header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.PayPal::buildQueryString($parsedBody));
        exit();
    }

    public function postPaypalPaymentResponse( Request $request, Response $response )
    {
	    // Response from Paypal
		$POST = $request->getParsedBody();
	    // read the post from PayPal system and add 'cmd'
	    $req = 'cmd=_notify-validate';
	    foreach ($POST  as $key => $value) {
		    $value = urlencode(stripslashes($value));
		    $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		    $req .= "&$key=$value";
	    }
	
	    // assign posted variables to local variables
	    $data['item_name']			= $POST['item_name'];
	    $data['item_number'] 		= $POST['item_number'];
	    $data['payment_status'] 	= $POST['payment_status'];
	    $data['payment_amount'] 	= $POST['mc_gross'];
	    $data['payment_currency']	= $POST['mc_currency'];
	    $data['txn_id']				= $POST['txn_id'];
	    $data['receiver_email'] 	= $POST['receiver_email'];
	    $data['payer_email'] 		= $POST['payer_email'];
	    $data['custom'] 			= $POST['custom'];
	
	    // post back to PayPal system to validate
	    $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	    $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
	
	    if (!$fp) {
		    // HTTP ERROR
		
	    } else {
		    fputs($fp, $header . $req);
		    while (!feof($fp)) {
			    $res = fgets($fp, 1024);
			    if (strcmp($res, "VERIFIED") == 0) {
				
				    // Used for debugging
				    // mail('user@domain.com', 'PAYPAL POST - VERIFIED RESPONSE', print_r($post, true));
				
				    // Validate payment (Check unique txnid & correct price)
				    $valid_txnid = check_txnid($data['txn_id']);
				    $valid_price = check_price($data['payment_amount'], $data['item_number']);
				    // PAYMENT VALIDATED & VERIFIED!
				    if ($valid_txnid && $valid_price) {
					
					    $p = Payment::create(['product_id' => 20]);
					
					    $p->save();
					
					    /*
					    if ($orderid) {
						    // Payment has been made & successfully inserted into the Database
					    } else {
						    // Error inserting into DB
						    // E-mail admin or alert user
						    // mail('user@domain.com', 'PAYPAL POST - INSERT INTO DB WENT WRONG', print_r($data, true));
					    }
				    } else {
					    // Payment made but data has been changed
					    // E-mail admin or alert user
				    }
					    */
					
				    } else if (strcmp($res, "INVALID") == 0) {
					
					    // PAYMENT INVALID & INVESTIGATE MANUALY!
					    // E-mail admin or alert user
					
					    // Used for debugging
					    //@mail("user@domain.com", "PAYPAL DEBUGGING", "Invalid Response<br />data = <pre>".print_r($post, true)."</pre>");
				    }
			    }
			    fclose($fp);
		    }
	    }
	    
    }

    public function getPaypalPaymentSuccessful( Request $request, Response $response )
    {
        $p = Payment::create(['product_id' => 3]);

        $p->save();
    }

    public function getPaypalPaymentCancelled( Request $request, Response $response )
    {
        $p = Payment::create(['product_id' => 4]);

        $p->save();
    }
}
