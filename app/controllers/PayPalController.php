<?php

namespace controllers;

use models\Product as Product;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

use models\PaypalPayment as PaypalPayment;
use core\PayPal as PayPal;

use core\Session as Session;

use core\Mail as Mail;

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

        $gross = ( (float)$formData['amount'] * (int)$formData['quantity'] );

		$Payment = PaypalPayment::create([
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

        $parsedUrl = parse_url($_SERVER['HTTP_REFERER']);
        $pp = __HOST__  . $parsedUrl['path'];

		PayPal::$successRoute = $_SERVER['HTTP_REFERER'] . '?s=' . $Payment->getSessionId() . '&pid=' . $Payment->getId();

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

        $Payment = PaypalPayment::find($payment_id);

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

        if($payment_status === 'completed')
        {
            $productName = $Product->getName();

            $body = "
			<b>Dear Customer!</b>
			<br>
		    Thank you for buying the following product: " .$productName . ".<br>
			You will be notified when your order is ready. <br>
			Thank you for your patience! <br><br>
		    Best Regards,<br>
			".getConfig('organisation.name').";";

            try {
                $mail = new Mail();
                $mail->setFrom(getConfig('organisation.email'), getConfig('organisation.name') . ' shop');
                $mail->Subject = 'Payment completed';
                $mail->addAddress($postData['payer_email'], 'Customer');
                $mail->Body = $body;
                $mail->send();
            } catch( \Exception $e ){

            }
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
