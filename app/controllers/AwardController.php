<?php

namespace controllers;

use models\AwardCollection;
use models\Match;
use models\Squad;
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
		    $where = ' WHERE a.event_name LIKE :name OR a.description LIKE :name OR e.name LIKE :name';
	    }

        $q1 = " SELECT SQL_CALC_FOUND_ROWS a.*, c.name AS game_name, c.name_short AS game_name_short, s.id AS squad_id, s.name AS squad_name, e.name AS event_title, e.start_date AS event_start, e.end_date AS event_end FROM _xyz_award a " .
            " LEFT JOIN _xyz_squad s ON s.id = a.squad_id " .
            " LEFT JOIN _xyz_category c ON c.id = s.game_id " .
            " LEFT JOIN _xyz_event e ON e.id = a.event_id " .
            " {$where} " .
            " ORDER BY a.id DESC " .
            " LIMIT ".static::INFINITE_LIMIT." OFFSET " . (int)$startAt
        ;

        $awards = DB::instance()->getAll($q1, $params);

        $total = DB::instance()->totalRowCount();

        foreach($awards as &$award)
        {
            $award['award_date'] = date("j F Y",  strtotime($award['award_date']));
            $award['event_start'] = date("j F Y", strtotime($award['event_start']));
            $award['event_end'] = date("j F Y",   strtotime($award['event_end']));

            /* @var \models\Squad $Squad */

            $Squad = Squad::find($award['squad_id']);

            $award['squad_header'] = "";
            if($Squad) $award['squad_header'] = $Squad->requestHeaderImage();

            $award['matches'] = [];

            if(!$award['event_id']) continue;

            $matches = Match::findAll($award['event_id'], 'event_id');

            if(!$matches) continue;

            foreach($matches as $Match)
            {
                /* @var \models\Match $Match */
                /* @var \models\EnemyTeam $EnemyTeam */

                $EnemyTeam = $Match->getEnemyTeam();

                $data = $Match->getProperties();
                $data['enemy_team_name'] = $EnemyTeam->getName();
                $data['squad_name'] = $award['squad_name'];

                $data['home_score'] = $Match->formatTotalHomeScore();
                $data['enemy_score'] = $Match->formatTotalEnemyScore();

                $datePlayed = $Match->getDatePlayed();

                $data['date_played'] = $datePlayed ? date("j F Y",   strtotime($datePlayed)) : "";

                $award['matches'][] = $data;
            }
        }

        //$awards = (AwardCollection::queryToCollection($q1, $params))->getFormatted();

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
