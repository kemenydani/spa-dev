<?php
/*
$app->get('/', 'controllers\PublicSPAController:index');
$app->get('/home', 'controllers\PublicSPAController:index');
*/

/*
$container = $app->getContainer();
$container['HomeController'] = function($container){
    return new controllers\HomeController($container);
};
*/
$app->get('/', 'controllers\HomeController:index');
$app->get('/home', 'controllers\HomeController:index');

$app->get('/admin', 'controllers\AdminSPAController:index');
