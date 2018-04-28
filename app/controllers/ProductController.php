<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\Product as Product;
//use models\ProductCollection as ProductCollection;

class ProductController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.product.list.html.twig', ['products' => []]);
    }
    
    public function getViewProduct( Request $request, Response $response, $args )
    {
    	// TODO: id would be safer
        $product = Product::find($args['name'], 'name');
	    $this->view->render($response, 'route.view.product.view.html.twig', ['product' => null]);
    }
}
