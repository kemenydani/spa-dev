<?php

$app->get('/auth', 'controllers\AuthController:auth' )->add( new middlewares\RequiresUnAuth() );
$app->post('/postLogin', 'controllers\AuthController:postLogin')->add( middlewares\RequiresUnAuth::class );
$app->post('/postRegister', 'controllers\AuthController:postRegister')->add( middlewares\RequiresUnAuth::class );
$app->get('/logout', 'controllers\AuthController:getLogout')->add( middlewares\RequiresAuth::class );
$app->get('/forgot', 'controllers\AuthController:getForgot')->add( middlewares\RequiresUnAuth::class );
$app->post('/resetpw', 'controllers\AuthController:postResetPassword')->add( middlewares\RequiresUnAuth::class );
$app->get('/activatepwreset/', 'controllers\AuthController:getActivatePasswordReset')->add( middlewares\RequiresUnAuth::class );
$app->post('/auth/checkUnique', 'controllers\AuthController:checkUnique' );