<?php

$app->get('/articles','controllers\ArticleController:index');

$app->group('/article', function ()
{
    $this->get('/read/{title_seo}', 'controllers\ArticleController:read');
	$this->get('/loadInfinity/', 'controllers\ArticleController:getLoadInfinite');
    $this->post('/postComment', 'controllers\ArticleController:postComment');
});
