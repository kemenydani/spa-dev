<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\Partner as Partner;

class PartnerController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $partners = Partner::getAll();

        $this->view->render($response, 'route.view.partner.list.html.twig', ['partners' => $partners]);
    }

    public function getViewPartner( Request $request, Response $response )
    {

    }
}