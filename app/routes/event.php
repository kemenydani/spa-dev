<?php

$app->get('/events','controllers\EventController:index');

$app->group('/event', function ()
{
    $this->get('/view/{name}', 'controllers\EventController:view');
    $this->get('/loadInfinity/', 'controllers\EventController:getLoadInfinite');
    $this->post('/postComment', 'controllers\EventController:postComment');
});
