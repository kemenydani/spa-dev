<?php

namespace controllers;

use core\Auth;
use models\Comment;
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
        $orderBy = $request->getQueryParam('order');
		$startAt = $request->getQueryParam('startAt') ? $request->getQueryParam('startAt') : 0;
		
		$data = $this->getMore($search, $startAt, $orderBy);
		
		return $response->withStatus(200)->withJson($data);
	}
	
	protected function getMore($search = null, $startAt = 0, $orderBy = null)
	{
		$params = [];
		$where = "";

		if($search)
		{
		    $params['name'] = '%'. $search .'%';
		    $where = ' WHERE name LIKE :name OR "desc" LIKE :name ';
        }

        $order = " ORDER BY name ASC ";

        if($orderBy)
        {
		    $orderBy = explode('|', $orderBy);
		    if(count($orderBy) === 2)
		    {
		        list($column,$direct) = $orderBy;
		        if(in_array($column, Product::$COLUMNS) && ($direct === 'asc' || $direct === 'desc'))
		        {
		            $order = ' ORDER BY ' . $column . ' ' . $direct . ' ';
                }
            }
        }
		
		$q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_product " .
			" {$where} " .
			" {$order} " .
			" LIMIT ".static::INFINITE_LIMIT." OFFSET " . (int)$startAt
		;

		/* @var \models\ProductCollection $products */
		$products = (ProductCollection::queryToCollection($q1, $params));
        $total = DB::instance()->totalRowCount();
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
		
		return ['models' => $data , 'total' => $total];
	}

    public function postComment( Request $request, Response $response )
    {
        $formData = $request->getParsedBody();

        $Product = Product::find($formData['model_id']);

        if(!$Product) return $response->withStatus(404, 'Product not found');

        $User = Auth::user();

        $formData['user_id'] = $User->getProperty('id');

        /* @var Comment $Comment */
        $Comment = $Product->addComment( Comment::create( $formData ) );

        $result = [];

        $data = $Comment->getProperties();
        $data['username'] = $User->getUsername();
        $data['profile_picture'] = $User->requestProfilePicture();

        return $response->withJson( $data );
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

        $comments = $product->getComments();

	    $this->view->render($response, 'route.view.product.view.html.twig', [
	        'token' => $token,
            'product' => $product,
            'images' => $images,
            'comments' => json_encode($comments, true)
        ]);
    }
}
