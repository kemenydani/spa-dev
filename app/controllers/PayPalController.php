<?php

namespace controllers;

use models\Product as Product;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

use models\Payment as Payment;
use core\PayPal as PayPal;
use core\Token as Token;


use core\Session as Session;

class PayPalController extends ViewController
{

	public function postPaymentRequest(Request $request, Response $response, $args)
	{
		$formData = $request->getParsedBody();

        if(!Token::validate($formData['token'])) die();

        $Product = Product::find($formData['item_number']);

        if(!$Product->getId()) return false;

        if(!$Product->hasAmountInStock($formData['quantity'])) return false;

        $gross = ( (float)$formData['amount'] * (int)$formData['quantity'] );

		$Payment = Payment::create([
			'product_id'     => $Product->getId(),
			'product_name'   => $Product->getName(),
			'single_price'   => (float)$Product->getPrice(),
			'currency'       => $Product->getCurrency(),
			'gross'          => $gross,
			'quantity'       => $formData['quantity'],
			'date_checkout'  => date('Y-m-d H:i:s'),
			'last_updated'   => date('Y-m-d H:i:s'),
			'session_id'     => session_id(),
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
	
	public function postIPNListener( Request $request, Response $response )
	{
		$PayPal = new PayPal();
        $PayPal->useSandbox();

        try
        {
            $verified = $PayPal->verifyIPN();
            if(!$verified) throw new \Exception('Failed to verify');
        }
        catch( \Exception $e )
        {
            return $response->withStatus(404, $e->getMessage());
        }

        // verified with existing post data

        $postData = $request->getParsedBody();

        $custom = $postData['custom'];
        $custom = explode(':', $custom);
        list($session_id, $payment_id) = $custom;

        $Payment = Payment::find($payment_id);

        if(!is_object($Payment)) return $response->withStatus(404, 'Could not find payment data.');

        $payment_status = strtolower($postData['payment_status']);

        $Payment->setProperty('txn_id',         $postData['txn_id']);
        $Payment->setProperty('ipn_track_id',   $postData['ipn_track_id']);
        $Payment->setProperty('payer_id',       $postData['payer_id']);
        $Payment->setProperty('payer_email',    $postData['payer_email']);
        $Payment->setProperty('currency',       $postData['mc_currency']);
        $Payment->setProperty('gross',          $postData['mc_gross']);
        $Payment->setProperty('payment_status', $payment_status);
        $Payment->setProperty('pending_reason', $postData['pending_reason']);
        $Payment->setProperty('last_updated',   date('Y-m-d H:i:s'));
        $Payment->setProperty('post',           json_encode($postData));

        if($postData['quantity']) $Payment->setProperty('quantity', $postData['quantity']);

        $Payment->save();

        $Product = Product::find($postData['item_number']);

        if(PayPal::paymentFailed($payment_status))
        {
            $newStockCount = (int)$Product->getProperty('in_stock') + (int)$Payment->getProperty('quantity');
            $Product->setProperty('in_stock', $newStockCount);
        }

        // Handshake sign for PayPal
        return $response->withStatus(200, 'OK');
	}
	
	public function getPaymentSuccessful(Request $request, Response $response, $args)
	{
	
	}
	
	public function getPaymentCancelled(Request $request, Response $response, $args)
	{
	
	}

}
