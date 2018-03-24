<?php

namespace controllers;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\DB as DB;

class ArticleController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $this->view->render($response, 'route.view.article.list.html.twig');
    }

    public function read ( Request $request, Response $response )
    {
        $comments = DB::instance()->all('comment');
/*
        $comments = array(
            1 => array('id' => 1, 'pid' => 0, 'ch' => array()),
            2 => array('id' => 2, 'pid' => 0, 'ch' => array()),
            3 => array('id' => 3, 'pid' => 0, 'ch' => array()),
            5 => array('id' => 5, 'pid' => 0, 'ch' => array()),
            11 => array('id' => 11, 'pid' => 0, 'ch' => array()),
            17 => array('id' => 17, 'pid' => 0, 'ch' => array()),
            23 => array('id' => 23, 'pid' => 0, 'ch' => array()),
            28 => array('id' => 28, 'pid' => 0, 'ch' => array()),
            4 => array('id' => 4, 'pid' => 1, 'ch' => array()),
            6 => array('id' => 6, 'pid' => 1, 'ch' => array()),
            8 => array('id' => 8, 'pid' => 2, 'ch' => array()),
            9 => array('id' => 9, 'pid' => 2, 'ch' => array()),
            7 => array('id' => 7, 'pid' => 3, 'ch' => array()),
            12 => array('id' =>12, 'pid' => 7, 'ch' => array()),
            13 => array('id' => 13, 'pid' => 12, 'ch' => array()),
        );
*/
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

        $this->view->render(
            $response,
            'route.view.article.read.html.twig',
            ['comments' => json_encode($comments, true)]
        );
    }
}
