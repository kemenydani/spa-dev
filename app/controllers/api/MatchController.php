<?php

namespace controllers\api;

use models\EnemyTeam;
use models\MatchMap;
use models\Squad;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Match as Match;

class MatchController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Match() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], [ 'enemy_team_id' => EnemyTeam::class, 'squad_id' => Squad::class ] );
    }

    public function getMaps( Request $request, Response $response, $args = [] ) : Response
    {
        $id = $request->getQueryParam('id');

        $dbMaps = MatchMap::findAll($id, 'match_id');

        $result = [];

        /* @var MatchMap $map */
        foreach($dbMaps as $map) $result[] = $map->getProperties();

        return $response->withJson($result);
    }

    public function storeMaps( Request $request, Response $response, $args = [] ) : Response
    {
        $form = $request->getParsedBodyParam('data');

        $result = [];

        foreach($form as $mapForm)
        {
            $Map = null;
            if(is_numeric($mapForm['id']))
            {
                $Map = MatchMap::find($mapForm['id']);
                $Map->setProperty('name', $mapForm['name']);
                $Map->setProperty('match_id', $mapForm['match_id']);
                $Map->setProperty('score_enemy', $mapForm['score_enemy']);
                $Map->setProperty('score_home', $mapForm['score_home']);
            }
            else
            {
                $Map = MatchMap::create([
                    'name' => $mapForm['name'],
                    'match_id' => $mapForm['match_id'],
                    'score_enemy' => $mapForm['score_enemy'],
                    'score_home' => $mapForm['score_home']
                ]);
            }
            $Map->save();
            $result[] = $Map->getProperties();
        }


        return $response->withJson($result);
    }

    public function storeMap( Request $request, Response $response, $args = [] ) : Response
    {
        $id = $request->getParsedBodyParam('id');
        $form = json_decode($request->getParsedBodyParam('data'), true);
        
        $Map = null;

        if($form['id'])
        {
            $Map = MatchMap::find($form['id']);
            $Map->setProperty('name', $form['name']);
            $Map->setProperty('score_enemy', $form['score_enemy']);
            $Map->setProperty('score_home', $form['score_home']);
        } 
        else 
        {
            $Map = MatchMap::create([
                'name' => $form['name'],
                'score_enemy' => $form['score_enemy'],
                'score_home' => $form['score_home']
            ]);
        }

        $Map->save();

        return $response->withJson($Map->getProperties());
    }

    public function deleteMap( Request $request, Response $response, $args = [] ) : Response
    {
        $id = $request->getParsedBodyParam('id');

        $Map = MatchMap::find($id);
        $matchId = $Map->getProperty('match_id');
        $Map->destroy();

        $result = [];

        $Maps = MatchMap::findAll($matchId, 'match_id');
        /* @var \models\MatchMap $M */
        if($Maps) foreach($Maps as $M) $result[] = $M->getProperties();

        return $response->withJson($result);
    }

}