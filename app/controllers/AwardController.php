<?php

namespace controllers;

use models\AwardCollection;
use Slim\Http\Response;
use Slim\Http\Request;
use core\DB as DB;
use models\Award;

class AwardController extends ViewController
{
    const INFINITE_LIMIT = 6;

    public function index ( Request $request, Response $response )
    {
	    $data  = $this->getMore();

        $this->view->render($response, 'route.view.award.list.html.twig',
            [
                'awards' => json_encode($data),
                'limit' => static::INFINITE_LIMIT
            ]
        );
    }

    public function getLoadInfinite( Request $request, Response $response )
    {
	    $search = $request->getQueryParam('search');
	    $startAt = $request->getQueryParam('startAt') ? $request->getQueryParam('startAt') : 0;
	    
	    $data = $this->getMore($search, $startAt);

	    return $response->withStatus(200)->withJson($data);

    }

    protected function getMore($search = null, $startAt = 0)
    {
        $params = [];
        $where = "";
       
	    if($search)
	    {
		    $params['name'] = '%'. $search .'%';
		    $where = ' WHERE event_name LIKE :name OR description LIKE :name ';
	    }

        $q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_award " .
            " {$where} " .
            " ORDER BY id DESC " .
            " LIMIT ".static::INFINITE_LIMIT." OFFSET " . (int)$startAt
        ;

        $awards = (AwardCollection::queryToCollection($q1, $params))->getFormatted();

        $total = DB::instance()->totalRowCount();

        return ['awards' => $awards, 'totalItems' => $total];
    }

    public function view ( Request $request, Response $response, $args )
    {
        $award = Award::find($args['id'], 'id');

        if(!$award) return false;

        $this->view->render(
            $response,
            'route.view.award.view.html.twig',
            [
                'award'  => $award->getProperties(),
            ]
        );
    }
}
