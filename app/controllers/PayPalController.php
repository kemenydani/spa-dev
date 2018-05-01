<?php

namespace controllers;

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

use models\Payment as Payment;
use core\PayPal as PayPal;

use core\Session as Session;



class PayPalController extends ViewController
{
	
	public function postPaymentRequest(Request $request, Response $response, $args)
	{
		$formData = $request->getParsedBody();
		
		// checks ...
		
		$response->redirect(PayPal::generateUrl($formData));
	}
	
	public function postPaymentResponse(Request $request, Response $response, $args)
	{
		// Response from Paypal
		$postData = $request->getParsedBody();
		
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		foreach ($postData as $key => $value)
		{
			$value = urlencode(stripslashes($value));
			$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
			$req .= "&$key=$value";
		}
		
		// assign posted variables to local variables
		$data['item_name']			= $postData['item_name'];
		$data['item_number'] 		= $postData['item_number'];
		$data['payment_status'] 	= $postData['payment_status'];
		$data['payment_amount'] 	= $postData['mc_gross'];
		$data['payment_currency']	= $postData['mc_currency'];
		$data['txn_id']				= $postData['txn_id'];
		$data['receiver_email'] 	= $postData['receiver_email'];
		$data['payer_email'] 		= $postData['payer_email'];
		$data['custom'] 			= $postData['custom'];
		
		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		// handshake?
		$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
		
		if (!$fp)
		{
			// HTTP ERROR
		}
		else
		{
			fputs($fp, $header . $req);
			while (!feof($fp))
			{
				$res = fgets ($fp, 1024);
				if (strcmp($res, "VERIFIED") == 0)
				{
					// Used for debugging
					mail('kemenydani93@gmail.com', 'PAYPAL POST - VERIFIED RESPONSE', print_r($_POST, true));
					/*
					// Validate payment (Check unique txnid & correct price)
					$valid_txnid = check_txnid($data['txn_id']);
					$valid_price = check_price($data['payment_amount'], $data['item_number']);
					*/
					$valid_txnid = true;
					$valid_price = true;
					// PAYMENT VALIDATED & VERIFIED!
					if ($valid_txnid && $valid_price)
					{
						/*
						$orderid = updatePayments($data);
						*/
						
						$p = Payment::create(['product_id' => 20]);
						$p->save();
						
						$orderid = $p->getId();
						
						if ($orderid) {
							// Payment has been made & successfully inserted into the Database
						} else {
							// Error inserting into DB
							// E-mail admin or alert user
							 mail('kemenydani93@gmail.com', 'PAYPAL POST - INSERT INTO DB WENT WRONG', print_r($data, true));
						}
					}
					else
					{
						// Payment made but data has been changed
						// E-mail admin or alert user
					}
					
				}
				else if (strcmp ($res, "INVALID") == 0)
				{
					
					// PAYMENT INVALID & INVESTIGATE MANUALY!
					// E-mail admin or alert user
					
					// Used for debugging
					@mail("kemenydani93@gmail.com", "PAYPAL DEBUGGING", "Invalid Response<br />data = <pre>".print_r($_POST, true)."</pre>");
				}
			}
			fclose ($fp);
		}
	}
	
	//
	
	public function getPaymentCancelled(Request $request, Response $response, $args)
	{
	
	}
	
	public function getPaymentSuccessful(Request $request, Response $response, $args)
	{
	
	}
	
	////
	
}
