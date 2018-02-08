<?php

namespace controllers\api;

use core\Session;
use core\DB as DB;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\Auth as Auth;
use models\User as User;

class UserController extends Controller implements CRUDInterface
{
    public function __construct()
    {
        parent::__construct( new User() );
    }

    public function getAuth( Request $request, Response $response )
    {
        $User = Auth::user();
        
        if( !$User )
	        return $response->withStatus(401, 'Authorization failed.');
	
	    return $response->withJson( $User->getProperties() );
    }

    public function postAuth( Request $request, Response $response )
    {
        $username = $request->getParsedBody()['user'];
        $password = $request->getParsedBody()['password'];
	    $remember = $request->getParsedBody()['remember'];
	    
        $remember = isset($remember) ? true : false;
        
        $User = User::find( $username, 'username' );
        
	    if( !$User->getId() )
	    	return $response->withStatus(404, 'Could not find user.');
	    
	    if( !$User->login( $password, $remember ) )
	    	return $response->withStatus(404, 'Authorization failed.');
	    
	    return $response->withJson( $User->getProperties() );
    }

    public function postLogin( Request $request, Response $response )
    {
        echo '/login';
        return $response;
    }
	
	public function postLogout( Request $request, Response $response )
	{
		$User = Auth::user();
		if( $User !== false ) {
			$User->logout();
			return true;
		}
		return $response->withStatus(404, 'User logout failed. Could not find logged in user.');
	}

    public function postRegister( Request $request, Response $response )
    {
        echo '/register';
        return $response;
    }

    public function getSearchPaginate( Request $request, Response $response )
    {
        $filterArray = json_decode($request->getQueryParam('filter'), true);

        $orderDirection = $filterArray['descending'] !== true     ? 'DESC' : 'ASC';
        $currentPage    = is_numeric($filterArray['page'])        ? (int)$filterArray['page'] : 1;
        $rowsPerPage    = is_numeric($filterArray['rowsPerPage']) ? (int)$filterArray['rowsPerPage'] : 5;
        $sortBy         = strlen($filterArray['sortBy']) > 0      ? $filterArray['sortBy'] : 'date_created';

        $startAtRow = ( ( $currentPage - 1 ) * $rowsPerPage );
        $orderDirection = " ORDER BY " . $sortBy . " " . $orderDirection . " ";
        $limit = " LIMIT " . $startAtRow . ", " . $rowsPerPage ." ";

        $search = $request->getQueryParam('search');
        $searchConditions = [];

        if( $search !== null && User::isSearchable() )
        {
            foreach( User::$_PROPS_SEARCHABLE as $column )
            {
                $searchConditions[] = $column . ' LIKE ' . "'%".$search."%'";
            }
        }

        $where = count($searchConditions) ? implode(' OR ', $searchConditions) : 1;

        $stmt = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_user WHERE $where $orderDirection $limit";

        $sql = DB::instance()->prepare( $stmt );
        $sql->execute();

        $items = $sql->fetchAll(\PDO::FETCH_ASSOC );

        $total = DB::instance()->query('SELECT FOUND_ROWS()')->fetch(\PDO::FETCH_COLUMN );

        return $response->withJson( [ 'total' => (int)$total, 'items' => $items ] );
    }

}