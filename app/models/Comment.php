<?php

namespace models;

use core\Model as Model;

class Comment extends Model
{
    public static $_UNIQUE_KEY = 'id';
    public static $_TABLE = 'comment';

    public static $_PROPS = ['id', 'pid', 'text', 'user_id'];
    public static $_PROPS_PROTECTED = [];

    public static function formatCommentTree( array $comments )
    {

        foreach ($comments as $k => &$v) {
            if ($v['pid'] != 0) {
                if(!array_key_exists('ch', $comments[$v['pid']])) $comments[$v['pid']]['ch'] = array();
                $comments[$v['pid']]['ch'][$v['id']] = &$v;
            }
        }

        unset($v);

        foreach ($comments as $k => $v) {
            if ($v['pid'] != 0) {
                unset($comments[$k]);
            }
        }

        $comments = array_reverse_recursive($comments);

        return $comments;
    }


}