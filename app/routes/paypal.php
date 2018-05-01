<?php

$app->group('/paypal', function()
{
	// request
    $this->post('/paymentRequest',    'controllers\PayPalController:postPaymentRequest');
    // ipn listen
	$this->post('/ipnlistener',   'controllers\PayPalController:postIPNListener');
    // success
    $this->post('/paymentsuccess', 'controllers\PayPalController:getPaymentSuccessful');
    
    //
    
    // cancel
    $this->get('/paymentCancelled',  'controllers\PayPalController:getPaymentCancelled');
    // notify/response
    $this->post('/paymentResponse',   'controllers\PayPalController:postPaymentResponse');
});