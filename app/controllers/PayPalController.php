<?php

namespace controllers;

use models\Product as Product;
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

        if(!hash_equals(Session::get('token'), $formData['token'])) {
            echo 'Token error';
            die();
        }

        $Product = Product::find($formData['item_number']);

        if(!$Product->getId()) return false;

       if(!$Product->hasAmountInStock($formData['quantity'])) return false;

/*
        foreach(['item_number', 'item_name', 'amount', 'quantity'] as $index => $key) {
            if(!array_key_exists($key, $formData)) {
                var_dump($key);
                die();
            }
        }






        if(!$Product) return false;
        if((float)$Product->getPrice() !== (float)$formData['amount'])   return false;
        if(!strcmp($Product->getCurrency(), $formData['currency_code'])) return false;
*/
        $gross = ( (float)$formData['amount'] * (int)$formData['quantity'] );

		$Payment = Payment::create([
			'product_id'     => (int)$Product->getId(),
			'product_name'   => (string)$Product->getName(),
			'single_price'   => (float)$Product->getPrice(),
			'currency'       => $Product->getCurrency(),
			'gross'          => $gross,
			'quantity'       => (int)$formData['quantity'],
			'date_checkout'  => (string)date('Y-m-d H:i:s'),
			'last_updated'   => (string)date('Y-m-d H:i:s'),
			'session_id'     => (string)session_id(),
			'payment_status' => 'unconfirmed'
		]);

		$Payment->save();

		$newStock = ((int)$Product->getProperty('in_stock') - (int)$Payment->getProperty('quantity'));

		$Product->setProperty('in_stock',  $newStock);

		$Product->save();

		// !! paypal will convert single price(amount) * quantity itself

		if(!$Payment->getId()) return $response->withStatus(404, 'Unable to handle your payment request. Please contact the administrator.');

		return $response->withRedirect(PayPal::generateUrl($formData, $Payment->getId()));
	}
	
	public function postIPNListener(Request $request, Response $response)
	{
		$PayPal = new PayPal();
        $PayPal->useSandbox();

        try
        {
            $verified = $PayPal->verifyIPN();
            if(!$verified) throw new \Exception('Failed to verify');
        }
        catch(\Exception $e )
        {
            return $response->withStatus(404, $e->getMessage());
        }

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

        $custom = $postData['custom'];
        $custom = explode(':', $custom);
        list($session_id, $payment_id) = $custom;

        $Payment = Payment::find($payment_id);

        $payment_status = strtolower($postData['payment_status']);

        $Payment->setProperty('txn_id', $postData['txn_id']);
        $Payment->setProperty('ipn_track_id', $postData['ipn_track_id']);
        $Payment->setProperty('payer_id', $postData['payer_id']);
        $Payment->setProperty('payer_email', $postData['payer_email']);
        $Payment->setProperty('currency', $postData['mc_currency']);

        if($postData['quantity']) $Payment->setProperty('quantity', $postData['quantity']);

        $Payment->setProperty('gross', $postData['mc_gross']);
        $Payment->setProperty('payment_status', $payment_status);
        $Payment->setProperty('pending_reason', $postData['pending_reason']);
        $Payment->setProperty('last_updated', date('Y-m-d H:i:s'));
        $Payment->setProperty('post', json_encode($postData));

        $Payment->save();

        $Product = Product::find($postData['item_number']);

        // in this cases we put back the items to stock
        // any other cases are not obvious
        $backStockReason = ['denied','expired','failed','voided'];

        if(in_array($payment_status, $backStockReason))
        {
            $newStock = (int)$Product->getProperty('in_stock') + (int)$Payment->getProperty('quantity');
            $Product->setProperty('in_stock', $newStock);
        }

        /*
         * https://github.com/paypal/ipn-code-samples/blob/master/php/example_usage_advanced.php
         * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
         */

        return $response->withStatus(200, 'OK');
	}
	
	public function getPaymentSuccessful(Request $request, Response $response, $args)
	{
	
	}
	
	public function getPaymentCancelled(Request $request, Response $response, $args)
	{
	
	}

}
