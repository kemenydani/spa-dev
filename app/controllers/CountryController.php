<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\DB as DB;
use models\Country as Country;

class CountryController extends ViewController
{

    public function getFind( Request $request, Response $response )
    {
	    $search = $request->getQueryParam('string');

	    $res = [];

	    if($search) $res = Country::getCountryByName($search);
	    
	    $d = [];

        foreach($res as $key => $value){
	        $d[] = [$key => $value];
        }
        
	    return $response->withStatus(200)->withJson($res);

    }

}
