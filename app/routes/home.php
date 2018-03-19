<?php
/*
$app->get('/', 'controllers\PublicSPAController:index');
$app->get('/home', 'controllers\PublicSPAController:index');
*/

$app->get('/', 'controllers\HomeController:index');
$app->get('/home', 'controllers\HomeController:index');

$app->get('/admin', 'controllers\AdminSPAController:index');
