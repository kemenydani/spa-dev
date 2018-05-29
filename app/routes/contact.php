<?php

$app->get('/contact', 'controllers\ContactController:index');
$app->get('/contactMessageSent', 'controllers\ContactController:contactMessageSent');
$app->post('/postContactMessage', 'controllers\ContactController:postContactMessage');