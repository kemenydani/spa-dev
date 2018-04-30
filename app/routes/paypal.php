<?php

$app->post('/payment/validatePaypalPayment', 'controllers\PaymentController:postValidatePaypalPayment');

$app->group('/payment', function(){

    $this->post('/paypalPaymentRequest',    'controllers\PaymentController:postPaypalPaymentRequest');
    $this->post('/paypalPaymentSuccessful', 'controllers\PaymentController:postPaypalPaymentSuccessful');
    $this->get('/paypalPaymentCancelled',  'controllers\PaymentController:getPaypalpaymentCancelled');
    $this->get('/paypalPaymentResponse',   'controllers\PaymentController:getPaypalPaymentResponse');
	
	
	
	$this->post('/paypalIPNListener',   'controllers\PayPalController:postListenPaypalIPN');
});