<?php

namespace controllers;

class ViewController
{
    public $view = null;

    public function __construct($container)
    {
        $this->view = $container['view'];
    }

}
