<?php

namespace controllers;

use models\Product;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

use models\Payment as Payment;
use core\PayPal as PayPal;

use core\PaypalIPN as PaypalIPN;

use core\Session as Session;

class PayPalController extends ViewController
{
	
	public function postPaymentRequest(Request $request, Response $response, $args)
	{
		$formData = $request->getParsedBody();
		
		$Payment = Payment::create([
			'product_id'     => (int)$formData['item_number'],
			'product_name'   => $formData['item_name'],
			'amount'         => (float)$formData['item_amount'],
			'gross'          => (float)$formData['item_amount'] * (int)$formData['quantity'],
			'quantity'       => (int)$formData['quantity'],
			'date_checkout'  => date('Y-m-d H:i:s'),
			'last_updated'   => date('Y-m-d H:i:s'),
			'session_id'     => session_id(),
			'payment_status' => 'unconfirmed'
		]);
		
		$Payment->save();
		
		if(!$Payment->getId()) return false;
		
		return $response->withRedirect(PayPal::generateUrl($formData, $Payment));
	}
	
	public function postIPNListener(Request $request, Response $response, $args)
	{
		$ipn = new PaypalIPN();
		// Use the sandbox endpoint during testing.
		$ipn->useSandbox();
		$verified = $ipn->verifyIPN();
		if ($verified)
		{
			$postData = $request->getParsedBody();
			
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
			
			$Payment = Payment::find($postData['item_number']);
			
			$payment_status = strtolower($postData['payment_status']);
			
			$Payment->setProperty('txn_id', $postData['txn_id']);
			$Payment->setProperty('payer_id', $postData['payer_id']);
			$Payment->setProperty('payer_email', $postData['payer_email']);
			$Payment->setProperty('currency', $postData['mc_currency']);
			$Payment->setProperty('quantity', $postData['quantity']);
			$Payment->setProperty('gross', $postData['mc_gross']);
			$Payment->setProperty('payment_status', $payment_status);
			$Payment->setProperty('pending_reason', $postData['pending_reason']);
			$Payment->setProperty('last_updated', date('Y-m-d H:i:s'));
			$Payment->setProperty('post', json_encode($postData));
			
			$Payment->save();
			
			/*
			 * Process IPN
			 * A list of variables is available here:
			 * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
			 */
		}
		else
		{
		
		}
		// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
		header("HTTP/1.1 200 OK");
	}
	
	public function getPaymentSuccessful(Request $request, Response $response, $args)
	{
	
	}
	
	public function getPaymentCancelled(Request $request, Response $response, $args)
	{
	
	}

}
