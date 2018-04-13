<?php

namespace models;

use core\Model as Model;
use models\User as User;

class Comment extends Model
{
    public static $PKEY = 'id';
    public static $TABLE = 'comment';
    public static $COLUMNS = ['id', 'pid', 'text', 'user_id'];

    protected $user = null;
    
    public function setUser( User $user )
    {
    	$this->user = $user;
    }
    
    public function getUser() : Model
    {
    	return User::find($this->getProperty('user_id'));
    }

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