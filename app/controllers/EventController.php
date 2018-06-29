<?php

namespace controllers;

use core\Auth;
use models\Comment;
use models\EventCollection;
use Slim\Http\Response;
use Slim\Http\Request;
use core\DB;
use models\Event;

class EventController extends ViewController
{
    const INFINITE_LIMIT = 6;

    public function index ( Request $request, Response $response )
    {
	    $data  = $this->getMore();

        $this->view->render($response, 'route.view.event.list.html.twig',
            [
                'events' => json_encode($data),
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
		    $where = ' WHERE name LIKE :name OR description LIKE :name ';
	    }

        $q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_event " .
            " {$where} " .
            " ORDER BY id DESC " .
            " LIMIT ".static::INFINITE_LIMIT." OFFSET " . (int)$startAt
        ;

        $events = (EventCollection::queryToCollection($q1, $params))->getFormatted();

        $total = DB::instance()->totalRowCount();

        return ['events' => $events, 'totalItems' => $total];
    }

    public function postComment( Request $request, Response $response )
    {
        $formData = $request->getParsedBody();

        $Event = Event::find($formData['model_id']);

        if(!$Event) return $response->withStatus(404, 'Event not found');

        $User = Auth::user();

        $formData['user_id'] = $User->getProperty('id');

        /* @var \models\Comment $Comment */
        $Comment = $Event->addComment( Comment::create( $formData ) );

        $data = $Comment->getProperties();
        $data['username'] = $User->getUsername();
        $data['profile_picture'] = $User->requestProfilePicture();

        return $response->withJson( $data );
    }

    public function view ( Request $request, Response $response, $args )
    {
        $event = Event::find($args['name'], 'name');

        $comments = $event->getComments();

        $this->view->render(
            $response,
            'route.view.event.view.html.twig',
            [
                'event'  => $event,
                'comments' => json_encode($comments, true)
            ]
        );
    }
}
