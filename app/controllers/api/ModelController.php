<?php

namespace controllers\api;

use core\Model;
use Slim\Http\Request;
use Slim\Http\Response;

use core\DB as DB;

abstract class ModelController implements ModelControllerInterface
{
    /**
     * @var Model
     */
    protected $Model;

    public function getModel() : Model
    {
        return $this->Model;
    }

    /**
     * ModelController constructor.
     * @param Model $Model
     */
    public function __construct( Model $Model )
    {
        $this->Model = $Model;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postActivate( Request $request, Response $response ) : Response
    {
        $range = $request->getParsedBody()['range'];

        $isActivated = ( $this->Model )::toggleActivateIn('id', $range, 1 );

        if( !$isActivated ) return $response->withStatus( 500, 'Database error: Could not activate items.' );

        return $response->withJson( $range );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postDeactivate( Request $request, Response $response ) : Response
    {
        $range = $request->getParsedBody()['range'];

        $isActivated = ( $this->Model )::toggleActivateIn('id', $range, 0 );

        if( !$isActivated ) return $response->withStatus( 500, 'Database error: Could not deactivate items.' );

        return $response->withJson( $range );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postDelete( Request $request, Response $response ) : Response
    {
        $range = $request->getParsedBody()['range'];

        $isDeleted = ( $this->Model )::deleteIn('id', $range );

        if( !$isDeleted ) return $response->withStatus( 500, 'Database error: Could not delete items.' );

        return $response->withJson( $range );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postCreate( Request $request, Response $response ) : Response
    {
        /* @var Model $Model */
        $Model = $this->Model::create( $request->getParsedBody() );

        $isInserted = $Model->save();

        if( $isInserted === false ) return $response->withStatus(500, 'Database error: Could not insert item.');

        return $response->withJson( $Model->getProperties() )->withStatus(200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postUpdate( Request $request, Response $response ) : Response
    {

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getOne( Request $request, Response $response ) : Response
    {
        $id = $request->getParsedBody()['id'];

        $Model = ( $this->Model )::find( $id );

        if( $Model === null ) return $response->withStatus(500, 'Database error: Could not find article.');

        $data = $Model->getProperties();

        return $response->withJson( $data )->withStatus(200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAll( Request $request, Response $response) : Response
    {
        /* @var Model[] $Models */
        $Models = $this->Model::getAll();

        $data = [];

        /* @var Model $Model */
        foreach( $Models as $Model ) $data[] = $Model->getProperties();

        return $response->withJson( $data );
    }

    public function searchPaginate( Request $request, Response $response, $args = [], $callback = null )
    {

    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = [] ) : Response
    {
        $filterArray = json_decode($request->getQueryParam('filter'), true);

        $orderDirection = $filterArray['descending'] !== true     ? 'DESC' : 'ASC';
        $currentPage    = is_numeric($filterArray['page'])        ? (int)$filterArray['page'] : 1;
        $rowsPerPage    = is_numeric($filterArray['rowsPerPage']) ? (int)$filterArray['rowsPerPage'] : 5;
        $sortBy         = strlen($filterArray['sortBy']) > 0      ? $filterArray['sortBy'] : 'id';

        $startAtRow = ( ( $currentPage - 1 ) * $rowsPerPage );
        $orderDirection = " ORDER BY main." . $sortBy . " " . $orderDirection . " ";
        $limit = " LIMIT " . $startAtRow . ", " . $rowsPerPage ." ";

        $search = $request->getQueryParam('search');
        $searchConditions = [];

        $joins = "";

        if( $search !== null )
        {
            foreach( $this->Model->getSearchColumns() as $key => $column  ) $searchConditions[] = 'main.' . $column . ' LIKE ' . "'%".$search."%'";

            $jk = 1;
            /* @var Model $Model */
            foreach($joinModels as $joinColumn => $Model)
            {
                $alias = 'join' . $jk;
                $joins .= ' LEFT JOIN ' . $Model::getTable() . ' '.$alias . ' ON ' . $alias . '.' . $Model::getPrimaryKey() . ' = main.' . $joinColumn;
                foreach( $Model::$SEARCH_COLUMNS as $key => $column  ) $searchConditions[] = $alias . '.' . $column . ' LIKE ' . "'%".$search."%'";
                $jk++;
            }

        }

        $where = count($searchConditions) ? implode(' OR ', $searchConditions) : 1;

        $stmt = " SELECT SQL_CALC_FOUND_ROWS * FROM ".$this->Model::getTable()." main $joins WHERE $where $orderDirection $limit";

        $sql = DB::instance()->prepare( $stmt );
        $sql->execute();

        $items = $sql->fetchAll(\PDO::FETCH_ASSOC );

        //$total = DB::instance()->query('SELECT FOUND_ROWS()')->fetch(\PDO::FETCH_COLUMN );

        $total = DB::instance()->totalRowCount();

        return $response->withJson( [ 'total' => (int)$total, 'items' => $items] );
    }

}
