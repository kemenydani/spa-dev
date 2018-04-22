<?php

$app->group('/squad', function ()
{
	$this->get('', 'controllers\SquadController:index');
	$this->get('/{name}', 'controllers\SquadController:getViewSquad');
});