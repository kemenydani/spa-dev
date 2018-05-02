<?php

use core\PayPal as PayPal;

$app->group('/paypal', function()
{
    $this->post('/paymentRequest',    'controllers\PayPalController:postPaymentRequest');
	$this->post('/ipnListener',       'controllers\PayPalController:postIPNListener');

    $this->get('/paymentSuccessful', 'controllers\PayPalController:getPaymentSuccessful');
    $this->get('/paymentCancelled',   'controllers\PayPalController:getPaymentCancelled');
});

PayPal::$successRoute = __HOST__ . '/paypal/paymentSuccessful';
PayPal::$cancelRoute  = __HOST__ . '/paypal/paymentCancelled';