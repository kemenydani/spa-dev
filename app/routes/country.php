<?php


$app->group('/country', function ()
{
    $this->get('/find/', 'controllers\CountryController:getFind');
});

