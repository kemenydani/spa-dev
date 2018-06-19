<?php

namespace controllers\api;

use models\Context as Context;

class ContextController extends ModelController
{

    public function __construct()
    {
        parent::__construct( new Context() );
    }

}