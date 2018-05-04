<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\Gallery as Gallery;
use models\GalleryCollection as GalleryCollection;

class GalleryController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $galleries = new GalleryCollection(Gallery::getAll());

        $this->view->render($response, 'route.view.gallery.list.html.twig', ['galleries' => $galleries]);
    }
    
    public function getViewSquad( Request $request, Response $response, $args )
    {
        $squad = Gallery::find($args['name'], 'name');
	    $this->view->render($response, 'route.view.gallery.view.html.twig', ['gallery' => $squad]);
    }
}