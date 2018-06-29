<?php

namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use models\Country as Country;

class CountryController extends ViewController
{

    public function getFind( Request $request, Response $response )
    {
	    $search = $request->getQueryParam('string');

	    $res = [];

	    if($search) $res = Country::getCountryByName($search);
	    
	    $d = [];

        foreach($res as $key => $value) $d[] = [$key => $value];

	    return $response->withStatus(200)->withJson($res);
    }

}
