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

    public function getSearchPaginate( Request $request, Response $response, array $args )
    {
        $filter = json_decode($request->getQueryParam('filter'), true);
        $search = json_decode($request->getQueryParam('search'), true);

        $order        = $filter['descending'] == true     ? 'DESC' : 'ASC';
        $page         = is_numeric($filter['page'])        ? (int)$filter['page'] : 1;
        // TODO: make default global setting for rowsPerPage
        $rowsPerPage  = is_numeric($filter['rowsPerPage']) ? (int)$filter['rowsPerPage'] : 5;
	
	    $start = (($page - 1) * $rowsPerPage);

        $order = " ORDER BY id " . $order . " ";
        $limit = " LIMIT ".$start.", ".$rowsPerPage." ";
        $where_conds = [];

        foreach( $search as $column => $value )
        {
            if( $value === null || $value === '') continue;
            $where_conds[] = $column . ' LIKE ' . "'%".$value."%'";
        }

      // return $response->withJson( $limit );

        $where = count($where_conds) ? implode(' AND ', $where_conds) : 1;

        $stmt = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_article WHERE $where $order $limit";

        //return $response->withJson( [ $stmt ] );

        $sql = DB::instance()->prepare( $stmt );
        $sql->execute();

        $items = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $total = DB::instance()->query('SELECT FOUND_ROWS()')->fetch(\PDO::FETCH_COLUMN);

        //$items = [];

        /*
        foreach($rows as $row)
        {
            $items[] = $row;
        }
*/
        return $response->withJson( [ 'total' => (int)$total, 'items' => $items ] );
    }
	
}