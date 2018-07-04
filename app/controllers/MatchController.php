<?php

namespace controllers;

use models\MatchMap;

use Slim\Http\Request;
use Slim\Http\Response;

use core\DB;

class MatchController extends ViewController
{
    const INFINITE_LIMIT = 20;

    public function index ( Request $request, Response $response )
    {
        $data  = $this->getMore();

        $this->view->render($response, 'route.view.match.list.html.twig',
            [
                'matches' => json_encode($data),
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

    protected function getMore($search = [], $startAt = 0)
    {
        $params = [];
        $where = "";
        
        if($search)
        {
            $name = $search;
            $where = ' WHERE sq.name LIKE :name OR et.name LIKE :name OR cat.name LIKE :name OR cat.name_short LIKE :name';
            $params['name'] = '%' . $name . '%';
        }

        // sql lekérdezés
        $q1 = " SELECT SQL_CALC_FOUND_ROWS " .
              " m.id, m.date_played, m.date_created, m.featured, " .
              " sq.logo AS logo_squad, sq.name AS squad_name,    " .
              " et.logo AS logo_enemy, et.name AS enemy_name,    " .
              " cat.name_short AS game_name_short, cat.name AS game_name, " .
              " COALESCE(SUM(mm.score_home), 0) AS csh, " .
              " COALESCE(SUM(mm.score_enemy), 0) AS cse, " .
              " COALESCE(COUNT(mm.id), 0) AS count_maps " .
              " FROM _xyz_match m " .
              " LEFT JOIN _xyz_squad      sq  ON sq.id = m.squad_id " .
              " LEFT JOIN _xyz_enemy_team et  ON et.id = m.enemy_team_id " .
              " LEFT JOIN _xyz_category   cat ON cat.id = m.game_id " .
              " LEFT JOIN _xyz_match_map  mm  ON match_id = m.id " .
                $where .
              " GROUP BY m.id " .
              " ORDER BY m.date_created DESC " .
              " LIMIT " . static::INFINITE_LIMIT . " OFFSET " . (int)$startAt
        ;
        // lekérdezés vége
        $matches = DB::instance()->getAll($q1, count($params) ? $params : null);
        $total = DB::instance()->totalRowCount();
        $res = [];
        // itt dolgozom fel az adatot
        foreach($matches as $index => $match)
        {
            $res[$index] = $match;
            $res[$index]['maps'] = [];
            /* @var MatchMap $map */
            foreach(MatchMap::findAll($match['id'], 'match_id') as $map) {
                $res[$index]['maps'][] = $map->getProperties([
                    'name',
                    'score_home',
                    'score_enemy'
                ]);
            }
        }
        // és itt köpöm ki a weboldalnak az eredményt amit megjelenít
        return ['matches' => $res, 'totalItems' => $total];
    }
}
