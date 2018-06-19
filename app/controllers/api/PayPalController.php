<?php

namespace controllers\api;

use models\PaypalPayment;

class PayPalController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new PaypalPayment() );
    }

}