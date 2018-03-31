<?php

$app->get('/auth', 'controllers\AuthController:auth' )->add( new middlewares\RequiresUnAuth() );


$app->post('/postLogin', 'controllers\AuthController:postLogin');
$app->post('/postRegister', 'controllers\AuthController:postRegister');
$app->get('/logout', 'controllers\AuthController:getLogout');

$app->post('/auth/checkUnique', 'controllers\AuthController:checkUnique' );