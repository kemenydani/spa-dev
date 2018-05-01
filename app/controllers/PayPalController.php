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
			'product_id'    => (int)$formData['item_number'],
			'product_name'  => (int)$formData['item_name'],
			'amount'        => (float)$formData['amount'],
			'quantity'      => (int)$formData['quantity'],
			'date_checkout' => date('Y-m-d H:i:s'),
			'session_id'    => session_id(),
		]);
		
		$Payment->save();
		
		if(!$Payment->getId()) return false;
		
		return $response->withRedirect(PayPal::generateUrl($formData));
	}
	
	public function postIPNListener(Request $request, Response $response, $args)
	{
		$ipn = new PaypalIPN();
		// Use the sandbox endpoint during testing.
		$ipn->useSandbox();
		$verified = $ipn->verifyIPN();
		if ($verified)
		{
			//$postData = $request->getParsedBody();
			
			
			
			/*
			 * Process IPN
			 * A list of variables is available here:
			 * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
			 */
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
