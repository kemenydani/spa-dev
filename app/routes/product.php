<?php

$app->get('/products', 'controllers\ProductController:index');

$app->group('/product', function ()
{
	$this->get('/{name}[/{foo}]', 'controllers\ProductController:getViewProduct');
    $this->get('/loadInfinity/', 'controllers\ProductController:getLoadInfinite');
});

