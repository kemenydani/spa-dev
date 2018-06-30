<?php

namespace controllers\api;

use core\DB;
use models\Squad;
use Slim\Http\Request;
use Slim\Http\Response;

use models\Event as Event;

class EventController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Event() );
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items)
        {
            foreach($items as &$event)
            {
                $result = [];
                $event['squads'] = [];

                $q = 'SELECT sq.id, sq.name FROM `_xyz_event_squad_pivot` esp LEFT JOIN _xyz_squad sq ON sq.id = esp.squad_id WHERE event_id = ?';

                $squads = DB::instance()->getAll($q, $event['id']);
                if(!is_array($squads)) $squads = [];
                /* @var Squad $squad */
                foreach($squads as $squad) $result[] = $squad['id'];//['id' => $squad['id'], 'name' => $squad['id']];

                $event['squads'] = $result;
            }

            return $items;
        });
    }

    public function postStore(Request $request, Response $response): Response
    {
        $formData = $request->getParsedBody();

        $Event = Event::create($formData);
        $id = $Event->save();

        $idArray = $Event->setSquadPivots($formData['squads']);
        $data = $Event->getFormatted();
        $data['squads'] = $idArray;

        if($id) return $response->withStatus(200)->withJson(['success' => true, 'data' => $data]);
        return $response->withStatus(500, 'Server Error: Could not save.');
    }


}