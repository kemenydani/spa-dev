<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\Product as Product;
use core\Session as Session;
//use models\ProductCollection as ProductCollection;

class ProductController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.product.list.html.twig', ['products' => []]);
    }
    
    public function getViewProduct( Request $request, Response $response, $args )
    {
        $product = Product::find($args['name'], 'name');

        if(!Session::exists('token')) Session::put('token', bin2hex(random_bytes(32)));

        $token = Session::get('token');

	    $this->view->render($response, 'route.view.product.view.html.twig', ['token' => $token, 'product' => $product->getFormatted()]);
    }
}
