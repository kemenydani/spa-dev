<?php

$app->get('/squads', 'controllers\SquadController:index');

$app->group('/squad', function ()
{
	$this->get('/{name}', 'controllers\SquadController:getViewSquad');
});