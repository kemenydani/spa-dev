<?php

$app->get('/products', 'controllers\ProductController:index');

$app->group('/product', function ()
{
	$this->get('/{name}', 'controllers\ProductController:getViewProduct');
});