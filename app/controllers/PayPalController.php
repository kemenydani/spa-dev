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
		
		return $response->withRedirect(PayPal::generateUrl($formData, 301));
	}
	
	public function postIPNListener(Request $request, Response $response, $args)
	{
		header('HTTP/1.1 200 OK');
		$pm = Payment::create(['product_id' => 20]);
		$pm->save();
		$postData = $request->getParsedBody();
		
		$resp = 'cmd=_notify-validate';
		foreach ($postData as $parm => $var)
		{
			$var = urlencode(stripslashes($var));
			$resp .= "&$parm=$var";
		}

		$item_name        = $_POST['item_name'];
		$item_number      = $_POST['item_number'];
		$payment_status   = $_POST['payment_status'];
		$payment_amount   = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id           = $_POST['txn_id'];
		$receiver_email   = $_POST['receiver_email'];
		$payer_email      = $_POST['payer_email'];
	    $record_id	 	  = $_POST['custom'];

		$httphead = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$httphead .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$httphead .= "Content-Length: " . strlen($resp) . "\r\n\r\n";
		$errno ='';
		$errstr='';
		
		$fh = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
		$pm = Payment::create(['product_id' => 21]);
		$pm->save();
		if (!$fh) {
			// Uh oh. This means that we have not been able to get thru to the PayPal server.  It's an HTTP failure
			//
			// You need to handle this here according to your preferred business logic.  An email, a log message, a trip to the pub..
		}
		else
		{
			fputs ($fh, $httphead . $resp);
			while (!feof($fh))
			{
				$readresp = fgets ($fh, 1024);
				if (strcmp ($readresp, "VERIFIED") == 0)
				{
					$pm = Payment::create(['product_id' => 25]);
					$pm->save();
/*
// 				Hurrah. Payment notification was both genuine and verified
//
//				Now this is where we record a record such that when our client gets returned to our success.php page (which might be momentarily
//  			(remember, PayPal tries to stall users for 10 seconds after purchase so the IPN gets through first) or much later, we can see if the
//				payment completed; and if it did, we can release the download.  You can go about this synchronisation between listener.php
//				and success.php in many different ways.  How you do it mostly depends on your need for security; but here is one way I do it:
//
//				When the client initiates the purchase by clicking the "buy" button, I write a new "unconfirmed" payment record in my Payments
//				table; this includes all the details of what they wish to purchase and their session-ID.  I then pass the record "id" of this pending entry in the CUSTOM
//				parameter to PayPal when it processes my site visitor tranaction.
//
//				After PayPal processes the transation, it doesn't return the client to your site immediately; it conveniently stalls them for around
//				10 seconds, during which it quickly calls your listener program (this program) to give it the good news.  I then extract the record_id
//				that was inserted in the Payments table database that was created just before the client was sent to PayPal, but now I know that
//				the payment is VERIFIED, so I can update the record in the PAYMENTS table from "Pending" to "Completed".
//
//				When (or if) the user returns to my "Auto Return" success.php page, I query the database for all "Completed" transactions with the
//				same Session_id, read the digital products that they have purchased and then release them as downloadable links in
//				success.php.
//
//				Yes, session_id is not totally reliable, but you could use cookies, or you could use a comprehensive user
//				registration, logon & password retrieval system that would give you the degree of "lock down" you require.  Your choice.
*/
				}
				
				else if (strcmp ($readresp, "INVALID") == 0)
				{
					//Man alive!  A hacking attempt?
				}
			}
			fclose ($fh);
		}
	}
	
	public function getPaymentSuccessful(Request $request, Response $response, $args)
	{
	
	}
	
	public function getPaymentCancelled(Request $request, Response $response, $args)
	{
	
	}

}
