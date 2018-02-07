<?php

namespace controllers\api;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Article as Article;
use core\Auth as Auth;
use core\DB as DB;

class ArticleController extends Controller
{
	public function postCreate( Request $request, Response $response )
	{
		$data = $request->getParsedBody();
		
		$title      = isset($data['title'])      ? $data['title']      : '';
		$teaser     = isset($data['teaser'])     ? $data['teaser']     : '';
		$content    = isset($data['content'])    ? $data['content']    : '';
		$categories = isset($data['categories']) ? $data['categories'] : '';
		
		$Article = Article::create([
			'title'      => $title,
			'teaser'     => $teaser,
			'content'    => $content,
			'created_by' => Auth::user()->getId()
		]);

		$id = $Article->save();
		
		$category_ids = [];
		
		foreach( $categories as $key => $value )
		{
			$category_ids[] = $value['id'];
		}
		
		$Article->categorize( $category_ids );
		
		if( $id === false ) return $response->withStatus(500, 'Database error: Could not insert article.');
		
		return $response->withJson( $Article->getProperties() )->withStatus(200);
	}

	public function postDelete( Request $request, Response $response )
    {
        $range = $request->getParsedBody()['range'];

        $isDeleted = Article::deleteIn('id', $range );

        if( !$isDeleted ) return $response->withStatus( 500, 'Database error: Could not delete items.' );

        return $response->withJson( $range );
    }

	public function getOne( Request $request, Response $response )
	{
		$id = $request->getParsedBody()['id'];
		
		$Article = Article::find( $id );
		
		if( $Article === null ) return $response->withStatus(500, 'Database error: Could not find article.');
		
		$responseData = $Article->getProperties();
		$responseData['categories'] = $Article->getCategories();
		
		return $response->withJson( $responseData )->withStatus(200);
	}
	
	public function getAll( Request $request, Response $response )
	{
	    $Articles = Article::all();

	    $data = [];

	    foreach( $Articles as $Article )
	    {
            $data[] = $Article->getPublicProperties();
        }

		return $response->withJson( $data );
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

        if( $search !== null && Article::isSearchable() )
        {
            foreach( Article::$_PROPS_SEARCHABLE as $column )
            {
                $searchConditions[] = $column . ' LIKE ' . "'%".$search."%'";
            }
        }

        $where = count($searchConditions) ? implode(' OR ', $searchConditions) : 1;

        $stmt = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_article WHERE $where $orderDirection $limit";

        $sql = DB::instance()->prepare( $stmt );
        $sql->execute();

        $items = $sql->fetchAll(\PDO::FETCH_ASSOC );

        $total = DB::instance()->query('SELECT FOUND_ROWS()')->fetch(\PDO::FETCH_COLUMN );

        return $response->withJson( [ 'total' => (int)$total, 'items' => $items ] );
    }
	
}