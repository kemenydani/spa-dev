<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \models\Comment;
use core\DB as DB;

class CommentController extends ViewController
{
    public function store ( Request $request, Response $response )
    {
        $data = $request->getParsedBody();

        $pid = isset($data['pid']) ? $data['pid'] : 0;
        $text = isset($data['text']) ? $data['text'] : '';

        $c = Comment::create([
            'pid' => $pid,
            'text' => $text,
            'user_id' => 16
        ]);

        $c->save();

        return $response->withJson($c->getPublicProperties())->withStatus(200);
    }

    public function sync ( Request $request, Response $response )
    {
        return $response->withJson(self::getAll())->withStatus(200);
    }

    public static function getAll()
    {
        $comments2 = DB::instance()->all('comment');

        $indexMatched = [];

        for($i = 0; $i < count($comments2); $i++) {

            $indexMatched[$comments2[$i]['id']] = $comments2[$i];
        }

        $comments = $indexMatched;

        foreach ($comments as $k => &$v) {
            if ($v['pid'] != 0) {
                $comments[$v['pid']]['ch'][] = &$v;
            }
        }

        unset($v);

        foreach ($comments as $k => $v) {
            if ($v['pid'] != 0) {
                unset($comments[$k]);
            }
        }

        return $comments;
    }
}