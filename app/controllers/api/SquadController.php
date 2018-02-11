<?php

namespace controllers\api;

use models\SquadMember;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Squad as Squad;

class SquadController extends ModelController
{

    public function __construct()
    {
        parent::__construct( new Squad() );
    }
    
    public function getAll(Request $request, Response $response) : Response
    {
	    $Squads = Squad::all();
	    
	    $data = [];
	    
	    foreach( $Squads as $Squad )
	    {
	    	$propertyArray =  $Squad->getPublicProperties();
		    $propertyArray['members'] = [];
	    	
	    	foreach( $Squad->getMembers() as $SquadMember ) $propertyArray['members'][] = $SquadMember->getPublicProperties();
	    	
	    	$data[] = $propertyArray;
	    }

	    return $response->withJson( $data )->withStatus(200);
    }
    
    public function addSquadMember( Request $request, Response $response )
    {
    	$squad_id = $request->getParsedBody();
    	
        $SquadMember = SquadMember::create( [ 'squad_id' => $squad_id ] );
        
        return $response->withJson( $SquadMember->getPublicProperties() )->withStatus(200);
    }
	
}
