<?php

namespace controllers;

class PartnerController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.partner.html.twig');
    }
}