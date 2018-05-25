<?php

namespace controllers;

use models\ProductImage;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

use models\Product as Product;
use core\Session as Session;
use models\ProductCollection as ProductCollection;

use core\DB;

class ProductController extends ViewController
{
	const INFINITE_LIMIT = 8;

    public function index ( Request $request, Response $response )
    {
        $data  = $this->getMore();

        $this->view->render($response, 'route.view.product.list.html.twig',
            [
                'products' => json_encode($data),
                'limit' => static::INFINITE_LIMIT
            ]
        );
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

		/* @var \models\ProductCollection $products */
		$products = (ProductCollection::queryToCollection($q1, $params));

		$data = [];

		foreach($products->getModels() as $i => $product)
		{
		    $data[$i] = $product->getFormatted();
            $data[$i]['image'] = null;
		    /* @var Product $product */
		    $previewImage = $product->getPreviewImage();
            /* @var ProductImage $previewImage */
		    if($previewImage)
		    {
                $data[$i]['image'] = $previewImage->requestImageUrl();
            }
        }

		$total = DB::instance()->totalRowCount();
		
		return ['models' => $data , 'total' => $total];
	}
    
    public function getViewProduct( Request $request, Response $response, $args )
    {
        //var_dump($request->getQueryParams());
        //TODO: manage return params to display success / whatever message on the product page

        $product = Product::find($args['id'], 'id');

        if(!Session::exists('token')) Session::put('token', bin2hex(random_bytes(32)));

        $token = Session::get('token');

        $images = $product->getImages();

        $imageUrls = [];

        foreach($images as $image){
            /* @var \models\ProductImage $image */
            $imageUrls[] = $image->requestImageUrl();
        }

	    $this->view->render($response, 'route.view.product.view.html.twig', ['token' => $token, 'product' => $product, 'images' => $images ]);
    }
}
