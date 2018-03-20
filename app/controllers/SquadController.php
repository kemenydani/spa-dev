<?php

namespace controllers;

class SquadController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.squad.html.twig');
    }
}