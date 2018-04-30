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

    //

    public function postPaypalPaymentRequest( Request $request, Response $response, $args)
    {
        $parsedBody = $request->getParsedBody();

        //$errors = $this->validateOrder($parsedBody);

        //if(count($errors)) return false;

        $parsedBody['amount'] = 10;

        echo PayPal::buildQueryString($parsedBody);

        header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.PayPal::buildQueryString($parsedBody));
        exit();
    }

    public function getPaypalPaymentResponse( Request $request, Response $response )
    {
        $p = Payment::create(['product_id' => 1]);

        $p->save();
    }

    public function postPaypalPaymentSuccessful( Request $request, Response $response )
    {
        $p = Payment::create(['product_id' => 1]);

        $p->save();
    }

    public function getPaypalPaymentCancelled( Request $request, Response $response )
    {
        $p = Payment::create(['product_id' => 1]);

        $p->save();
    }
}
