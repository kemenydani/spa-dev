<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\Product as Product;

use core\Token as Token;

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

        $token = Token::get();

	    $this->view->render($response, 'route.view.product.view.html.twig', ['token' => $token, 'product' => $product->getFormatted()]);
    }
}
