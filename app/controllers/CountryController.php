<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\DB as DB;
use models\Country as Country;

class CountryController extends ViewController
{

    public function index ( Request $request, Response $response )
    {
        $q1 = " SELECT * FROM _xyz_article art " .
            " WHERE art.highlighted = ? "      .
            " ORDER BY art.date_created DESC " .
            " LIMIT 2 "
        ;

        $headlines = (ArticleCollection::queryToCollection($q1, 1))->getFormatted();

	    $data  = $this->getMore();

        $this->view->render($response, 'route.view.article.list.html.twig',
            [
                'headlines' => $headlines,
                'articles' => json_encode($data),
                'limit' => static::INFINITE_LIMIT
            ]
        );
    }

    public function getFind( Request $request, Response $response )
    {
	    $search = $request->getQueryParam('string');

	    $res = [];

	    if($search) {
	        $res = Country::getCountryByName($search);
        }

	    return $response->withStatus(200)->withJson($res);

    }

}
