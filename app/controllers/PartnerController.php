<?php

namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;

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