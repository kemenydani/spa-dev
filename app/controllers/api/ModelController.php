<?php

namespace controllers\api;

use Slim\Http\Request;
use Slim\Http\Response;

use core\DB as DB;
use core\Model as Model;

abstract class ModelController implements ModelControllerInterface
{
    protected $Model;

    public function getModel() : Model
    {
        return $this->Model;
    }

    public function __construct( Model $Model )
    {
        $this->Model = $Model;
    }

    public function postActivate( Request $request, Response $response ) : Response
    {
        $range = $request->getParsedBody()['range'];

        $isActivated = ( $this->Model )::toggleActivateIn('id', $range, 1 );

        if( !$isActivated ) return $response->withStatus( 500, 'Database error: Could not activate items.' );

        return $response->withJson( $range );
    }

    public function postDeactivate( Request $request, Response $response ) : Response
    {
        $range = $request->getParsedBody()['range'];

        $isActivated = ( $this->Model )::toggleActivateIn('id', $range, 0 );

        if( !$isActivated ) return $response->withStatus( 500, 'Database error: Could not deactivate items.' );

        return $response->withJson( $range );
    }

    public function postDelete( Request $request, Response $response ) : Response
    {
        $range = $request->getParsedBody()['range'];

        $isDeleted = ( $this->Model )::deleteIn('id', $range );

        if( !$isDeleted ) return $response->withStatus( 500, 'Database error: Could not delete items.' );

        return $response->withJson( $range );
    }
 
    public function postCreate( Request $request, Response $response ) : Response
    {
        $Model = $this->Model::create( $request->getParsedBody() );

        $isInserted = $Model->save();

        if( $isInserted === false ) return $response->withStatus(500, 'Database error: Could not insert item.');

        return $response->withJson( $Model->getPublicProperties() )->withStatus(200);
    }

    public function postUpdate( Request $request, Response $response ) : Response
    {

    }

    public function getOne( Request $request, Response $response ) : Response
    {
        $id = $request->getParsedBody()['id'];

        $Model = ( $this->Model )::find( $id );

        if( $Model === null ) return $response->withStatus(500, 'Database error: Could not find article.');

        $data = $Model->getProperties();

        return $response->withJson( $data )->withStatus(200);
    }

    public function getAll( Request $request, Response $response) : Response
    {
        $Models = ( $this->Model )::all();

        $data = [];

        foreach( $Models as $Model )
        {
            $data[] = $Model->getProperties();
        }

        return $response->withJson( $data );
    }

    public function getSearchPaginate( Request $request, Response $response ) : Response
    {
        $filterArray = json_decode($request->getQueryParam('filter'), true);

        $orderDirection = $filterArray['descending'] !== true     ? 'DESC' : 'ASC';
        $currentPage    = is_numeric($filterArray['page'])        ? (int)$filterArray['page'] : 1;
        $rowsPerPage    = is_numeric($filterArray['rowsPerPage']) ? (int)$filterArray['rowsPerPage'] : 5;
        $sortBy         = strlen($filterArray['sortBy']) > 0      ? $filterArray['sortBy'] : 'id';

        $startAtRow = ( ( $currentPage - 1 ) * $rowsPerPage );
        $orderDirection = " ORDER BY " . $sortBy . " " . $orderDirection . " ";
        $limit = " LIMIT " . $startAtRow . ", " . $rowsPerPage ." ";

        $search = $request->getQueryParam('search');
        $searchConditions = [];

        if( $search !== null /*&& $this->Model::isSearchable()*/ )
        {
            foreach( /*$this->Model::getSearchableProps()*/Model::$COLUMNS as $column )
            {
                if( !array_key_exists( $column, /*$this->Model::getPropertyNames()*/Model::$COLUMNS ) ) continue;
                $searchConditions[] = $column . ' LIKE ' . "'%".$search."%'";
            }
        }

        $where = count($searchConditions) ? implode(' OR ', $searchConditions) : 1;

        $stmt = " SELECT SQL_CALC_FOUND_ROWS * FROM ".$this->Model::getTable()." WHERE $where $orderDirection $limit";

        $sql = DB::instance()->prepare( $stmt );
        $sql->execute();

        $items = $sql->fetchAll(\PDO::FETCH_ASSOC );

        $total = DB::instance()->query('SELECT FOUND_ROWS()')->fetch(\PDO::FETCH_COLUMN );

        return $response->withJson( [ 'total' => (int)$total, 'items' => $items ] );
    }

}
