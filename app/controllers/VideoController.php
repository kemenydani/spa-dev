<?php

namespace controllers;

class VideoController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.video.html.twig');
    }
}