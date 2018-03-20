<?php

namespace controllers;

class UserController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.user.html.twig');
    }
}