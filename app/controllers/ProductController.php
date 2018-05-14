<?php

namespace controllers;

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

use models\Product as Product;
use core\Session as Session;
use models\ProductCollection as ProductCollection;

class ProductController extends ViewController
{
	const INFINITE_LIMIT = 2;
	
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.product.list.html.twig', ['products' => []]);
    }
	
	public function getLoadInfinite( Request $request, Response $response )
	{
		$search = $request->getQueryParam('search');
		$startAt = $request->getQueryParam('startAt') ? $request->getQueryParam('startAt') : 0;
		
		$data = $this->getMore($search, $startAt);
		
		return $response->withStatus(200)->withJson($data);
	}
	
	protected function getMore($search = [], $startAt = 0)
	{
		$params = [];
		$where = "";
		$i = 0;
		foreach($search as $key => $value)
		{
			if(empty($value)) continue;
			$params[] = $value;
			$where .= $i === 0 ? ' WHERE ' . $key . ' = ? ' : ' AND ' . $key . ' = ? ';
			$i++;
		}
		
		$q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_product " .
			" {$where} " .
			" ORDER BY id DESC " .
			" LIMIT ".static::INFINITE_LIMIT." OFFSET " . (int)$startAt
		;
		
		$products = (ProductCollection::queryToCollection($q1, $params))->getFormatted();
		
		$total = DB::instance()->totalRowCount();
		
		return ['models' => $products , 'total' => $total];
	}
    
    public function getViewProduct( Request $request, Response $response, $args )
    {
        //var_dump($request->getQueryParams());
        //TODO: manage return params to display success / whatever message on the product page

        $product = Product::find($args['name'], 'name');

        if(!Session::exists('token')) Session::put('token', bin2hex(random_bytes(32)));

        $token = Session::get('token');

	    $this->view->render($response, 'route.view.product.view.html.twig', ['token' => $token, 'product' => $product->getFormatted()]);
    }
}
