<?php

$app->get('/partners', 'controllers\PartnerController:index');

$app->group('/partner', function ()
{
    $this->get('', 'controllers\PartnerController:index');
    $this->get('/{name}', 'controllers\PartnerController:getViewPartner');
});