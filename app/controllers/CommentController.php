<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use models\Comment;
use core\DB as DB;
use core\Auth;

class CommentController extends ViewController
{
    public function store ( Request $request, Response $response )
    {
        $User = Auth::user();

        if(!$User) return false;

        $data = $request->getParsedBody();

        $pid = isset($data['pid']) ? $data['pid'] : 0;
        $text = isset($data['text']) ? $data['text'] : '';

        if((int)$pid === (int)$User->getId()) return false;

        $c = Comment::create([
            'pid' => $pid,
            'text' => $text,
            'user_id' => 16
        ]);

        $c->save();

        $data = $c->getProperties();
        $data['profile_picture'] = $User->requestProfilePicture();

        return $response->withJson($data)->withStatus(200);
    }

    public function sync ( Request $request, Response $response )
    {
        return $response->withJson(self::getAll())->withStatus(200);
    }

}