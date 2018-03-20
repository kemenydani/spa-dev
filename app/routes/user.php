<?php

$app->get('/profile', 'controllers\UserController:index');
$app->get('/auth', 'controllers\UserController:auth');