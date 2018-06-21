<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;
use models\Country;

class CountryController
{

    public function getAll(){

        $res = [];

        foreach(Country::getAll() as $countryCode => $countryName)
        {
            $res[] = ['country_code' => $countryCode, 'country_name' => $countryName];
        }

        return $res;
    }

    public function findAll(Request $request, Response $response, $args)
    {
        $search = $request->getQueryParam('search');

        $found = Country::find($search);

        $res = [];

        if($found) $res[] = ['country_code' => $search, 'country_name' => $found];
        return $response->withStatus(200)->withJson($res);
    }

    public function likeAll(Request $request, Response $response, $args)
    {
        $search = $request->getQueryParam('search');

        $res = [];

        if(!empty($search))
        {
            $found = Country::getCountryByName($search);

            if(is_array($found))
            {
                foreach($found as $countryCode => $countryName)
                {
                    $res[] = ['country_code' => $countryCode, 'country_name' => $countryName];
                }
            }
        }
        else
        {
            $res = $this->getAll();
        };


        return $response->withStatus(200)->withJson($res);
    }

}