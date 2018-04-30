<?php

$app->post('/payment/validatePaypalPayment', 'controllers\PaymentController:postValidatePaypalPayment');

$app->group('/payment', function(){

    $this->post('/paypalPaymentRequest',    'controllers\PaymentController:postPaypalPaymentRequest');
    $this->get('/paypalPaymentSuccessful', 'controllers\PaymentController:getPaypalPaymentSuccessful');
    $this->get('/paypalPaymentCancelled',  'controllers\PaymentController:getPaypalpaymentCancelled');
    $this->get('/paypalPaymentResponse',   'controllers\PaymentController:postPaypalPaymentResponse');

});