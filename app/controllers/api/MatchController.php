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
        $id = $request->getParsedBodyParam('id');
        $formMaps = json_decode($request->getParsedBodyParam('maps'), true);

        $aliveIds = [];
        foreach($formMaps as $fMap)
        {
            if($fMap['id']) $aliveIds[] = $fMap['id'];
            $Map = $fMap['id'] ? MatchMap::find($fMap['id']) : MatchMap::create();
            $Map->setProperty('name', $fMap['name']);
            $Map->setProperty('score_enemy', $fMap['score_enemy']);
            $Map->setProperty('score_home', $fMap['score_home']);
            $Map->save();
        }

        $dbMaps = MatchMap::findAll($id, 'match_id');

        $result = [];

        /* @var \models\MatchMap $Map */
        foreach($dbMaps as &$Map)
        {
            if(!in_array($Map->getId(), $aliveIds)) {
                $Map->destroy();
                unset($Map);
            } else {
                $result[] = $Map->getProperties();
            }
        }

        return $response->withJson([]);
    }

}