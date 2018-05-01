<?php

$app->group('/paypal', function()
{
	// request
    $this->post('/paymentRequest',    'controllers\PayPalController:postPaymentRequest');
    // success
    $this->post('/paymentSuccessful', 'controllers\PayPalController:postPaymentSuccessful');
    // cancel
    $this->get('/paymentCancelled',  'controllers\PayPalController:getPaymentCancelled');
    // notify/response
    $this->post('/paymentResponse',   'controllers\PayPalController:postPaymentResponse');

});