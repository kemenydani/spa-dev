<?php

$app->group('/api', function ()
{
    $this->get('/build', 'controllers\PublicSPAController:build');

    $this->group('/user', function()
    {
        $this->get('/auth', 'controllers\api\UserController:getAuth');
        $this->post('/auth', 'controllers\api\UserController:postAuth');
        $this->post('/register', 'controllers\api\UserController:postRegister');
        $this->post('/login', 'controllers\api\UserController:postLogin');
	    $this->post('/logout', 'controllers\api\UserController:postLogout');

        $this->post('/delete', 'controllers\api\UserController:postDelete');
        $this->get('/search_paginate', 'controllers\api\UserController:getSearchPaginate');
        $this->get('/all', 'controllers\api\UserController:getAll');

        $this->post('/activate', 'controllers\api\UserController:postActivate');
        $this->post('/deactivate', 'controllers\api\UserController:postDeactivate');
    });
	
	$this->group('/article', function()
	{
		$this->get('/all', 'controllers\api\ArticleController:getAll');
        $this->get('/search_paginate', 'controllers\api\ArticleController:getSearchPaginate');
		$this->post('/create', 'controllers\api\ArticleController:postCreate');
        $this->post('/delete', 'controllers\api\ArticleController:postDelete');
        $this->post('/activate', 'controllers\api\ArticleController:postActivate');
        $this->post('/deactivate', 'controllers\api\ArticleController:postDeactivate');
	});

    $this->group('/gallery', function()
    {
        $this->get('/all', 'controllers\api\GalleryController:getAll');
        $this->get('/search_paginate', 'controllers\api\GalleryController:getSearchPaginate');
        $this->post('/create', 'controllers\api\GalleryController:postCreate');
        $this->post('/delete', 'controllers\api\GalleryController:postDelete');
        $this->post('/activate', 'controllers\api\GalleryController:postActivate');
        $this->post('/deactivate', 'controllers\api\GalleryController:postDeactivate');
    });

    $this->group('/squad', function()
    {
        $this->get('/all', 'controllers\api\SquadController:getAll');
        $this->get('/search_paginate', 'controllers\api\SquadController:getSearchPaginate');
        $this->post('/create', 'controllers\api\SquadController:postCreate');
        $this->post('/delete', 'controllers\api\SquadController:postDelete');
        $this->post('/activate', 'controllers\api\SquadController:postActivate');
        $this->post('/deactivate', 'controllers\api\SquadController:postDeactivate');
    });

    $this->group('/event', function()
    {
        $this->get('/all', 'controllers\api\EventController:getAll');
        $this->get('/search_paginate', 'controllers\api\EventController:getSearchPaginate');
        $this->post('/create', 'controllers\api\EventController:postCreate');
        $this->post('/delete', 'controllers\api\EventController:postDelete');
        $this->post('/activate', 'controllers\api\EventController:postActivate');
        $this->post('/deactivate', 'controllers\api\EventController:postDeactivate');
    });

    $this->group('/award', function()
    {
        $this->get('/all', 'controllers\api\AwardController:getAll');
        $this->get('/search_paginate', 'controllers\api\AwardController:getSearchPaginate');
        $this->post('/create', 'controllers\api\AwardController:postCreate');
        $this->post('/delete', 'controllers\api\AwardController:postDelete');
        $this->post('/activate', 'controllers\api\AwardController:postActivate');
        $this->post('/deactivate', 'controllers\api\AwardController:postDeactivate');
    });

    $this->group('/match', function()
    {
        $this->get('/all', 'controllers\api\MatchController:getAll');
        $this->get('/search_paginate', 'controllers\api\MatchController:getSearchPaginate');
        $this->post('/create', 'controllers\api\MatchController:postCreate');
        $this->post('/delete', 'controllers\api\MatchController:postDelete');
    });

    $this->group('/enemy', function()
    {
        $this->get('/all', 'controllers\api\EnemyController:getAll');
        $this->get('/search_paginate', 'controllers\api\EnemyController:getSearchPaginate');
        $this->post('/create', 'controllers\api\EnemyController:postCreate');
        $this->post('/delete', 'controllers\api\EnemyController:postDelete');
    });

    $this->group('/setting', function()
    {
        $this->get('/fetchSettings', 'controllers\api\SettingController:getFetchSettings');
        $this->post('/updateSetting', 'controllers\api\SettingController:postUpdateSetting');
    });
	
	$this->group('/category', function()
	{
        $this->get('/all', 'controllers\api\CategoryController:getAll');
        $this->get('/search_paginate', 'controllers\api\CategoryController:getSearchPaginate');
        $this->post('/create', 'controllers\api\CategoryController:postCreate');
        $this->post('/delete', 'controllers\api\CategoryController:postDelete');
	});

    $this->group('/comment', function()
    {
        $this->get('/all', 'controllers\api\CommentController:getAll');
        $this->get('/search_paginate', 'controllers\api\CommentController:getSearchPaginate');
        $this->post('/delete', 'controllers\api\CommentController:postDelete');
    });


})->add(function ( $request, $response, $next )
{
	$response = $next( $request, $response );
	
		return $response
			->withHeader( 'Access-Control-Allow-Origin', '*')
			->withHeader( 'Access-Control-Allow-Credentials', 'true')
			->withHeader( 'Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization' )
			->withHeader( 'Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS' );
		
});