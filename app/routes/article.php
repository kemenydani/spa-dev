<?php

$app->group('/article', function ()
{
    $this->get('',     'controllers\ArticleController:index');
    $this->get('/read', 'controllers\ArticleController:read');
});
