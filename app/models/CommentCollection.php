<?php

namespace models;

use core\ModelCollection;

class CommentCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Comment::class));
    }

    public static function toTree(array $comments)
    {

        foreach ($comments as $k => &$v)
        {
            $v['profile_picture'] = User::getProfilePictureUrl($v['profile_picture']);
            if ($v['pid'] != 0)
            {
                if(!array_key_exists('ch', $comments[$v['pid']])) $comments[$v['pid']]['ch'] = array();
                $comments[$v['pid']]['ch'][$v['id']] = &$v;
            }
        }

        unset($v);

        foreach ($comments as $k => $v) if ($v['pid'] != 0) unset($comments[$k]);

        $comments = array_reverse_recursive($comments);

        return $comments;
    }

    public function getPropertiesWithUserProps()
    {
        $found = [];

        // TODO:: rework this to have the images and names
        foreach($this->getModels() as $Comment)
        {
            $userId = $Comment->getProperty('user_id');

            $User = array_key_exists($userId, $found) ? $found[$userId] : $Comment->getUser();

            $found[$userId] = $User;

            if(!$User) continue;

            $Comment->setProperty('username', $User->getUsername());
            $Comment->setProperty('profile_picture', $User->formatProfilePicture());
        }
        // TODO:: now allows to set only model props defined in $COLUMNS
        return $this->getProperties();
    }

}
