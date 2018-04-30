<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use overint\PaypalIPN as PaypalIPN;

use models\Product as Product;
use models\Payment as Payment;
use core\PayPal as Paypal;

use core\Session as Session;

class PayPalController extends ViewController
{
   
    public function postListenPaypalIPN( Request $request, Response $response )
    {
	    $ipn = new PaypalIPN();
	    $ipn->useSandbox();
	    $verified = $ipn->verifyIPN();
	    if ($verified)
	    {
		    $p = Payment::create(['product_id' => 20]);
		    $p->save();
	    }
    }
}
