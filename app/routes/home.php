<?php

$app->get('/', 'controllers\HomeController:index');
$app->get('/home', 'controllers\HomeController:index');
$app->get('/admin', 'controllers\AdminSPAController:index');


$app->post('/storeComment', 'controllers\CommentController:store');
$app->post('/syncComments', 'controllers\CommentController:sync');