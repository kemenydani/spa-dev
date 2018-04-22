<?php

$app->group('/article', function ()
{
    $this->get('',     'controllers\ArticleController:index');
	$this->get('/loadInfinity/', 'controllers\ArticleController:getLoadInfinite');
    $this->get('/read/{title_seo}', 'controllers\ArticleController:read');
    $this->post('/postComment', 'controllers\ArticleController:postComment');
});
