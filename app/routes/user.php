<?php

$app->get('/profile/{username}', 'controllers\UserController:index');

$app->post('/uploadUserPicture', 'controllers\UserController:uploadPicture')->add( new \middlewares\RequiresAuth() );
