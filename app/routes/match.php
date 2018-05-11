<?php

$app->get('/matches', 'controllers\MatchController:index');

$app->group('/match', function ()
{
	$this->get('/{name}', 'controllers\MatchController:getViewMatch');
    $this->get('/loadInfinity/', 'controllers\MatchController:getLoadInfinite');
});