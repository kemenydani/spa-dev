<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use core\Model;

interface ModelControllerInterface
{
	public function getModel   ( ) : Model;
	
	public function postCreate ( Request $request, Response $response ) : Response;
	
	public function postUpdate ( Request $request, Response $response ) : Response;
	
	public function postDelete ( Request $request, Response $response ) : Response;
	
	public function getOne     ( Request $request, Response $response ) : Response;
	
	public function getAll     ( Request $request, Response $response ) : Response;
	
}