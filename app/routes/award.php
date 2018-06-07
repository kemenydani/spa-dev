<?php

$app->get('/awards','controllers\AwardController:index');

$app->group('/award', function ()
{
$this->get('/view/{id}', 'controllers\AwardController:view');
$this->get('/loadInfinity/', 'controllers\AwardController:getLoadInfinite');
});
