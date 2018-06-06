<?php

$app->get('/profile/{username}', 'controllers\UserController:index');

$app->post('/uploadUserPicture', 'controllers\UserController:uploadPicture');

$app->post('/user/updateProfile', 'controllers\UserController:postUpdateProfile');