<?php

$app->group('/api', function ()
{
    $this->group('/user', function()
    {

            //->add( new \middlewares\RequiresAuth())
            //->add( new \middlewares\RequiresRole(['users.list']));

        //$this->get('/{id}', 'controllers\api\UserController:getUser');
        $this->get('/auth', 'controllers\api\UserController:getAuth');
        $this->post('/auth', 'controllers\api\UserController:postAuth');
        $this->post('/register', 'controllers\api\UserController:postRegister');
        $this->post('/login', 'controllers\api\UserController:postLogin');
	    $this->post('/logout', 'controllers\api\UserController:postLogout');

        $this->post('/delete', 'controllers\api\UserController:postDelete');
        $this->get('/search_paginate', 'controllers\api\UserController:getSearchPaginate');
        $this->get('/all', 'controllers\api\UserController:getAll');
    });
	
	$this->group('/article', function()
	{
		$this->get('/all', 'controllers\api\ArticleController:getAll');
        $this->get('/search_paginate', 'controllers\api\ArticleController:getSearchPaginate');
		$this->post('/create', 'controllers\api\ArticleController:postCreate');
        $this->post('/delete', 'controllers\api\ArticleController:postDelete');
	});
	
	$this->group('/category', function()
	{
        $this->get('/all', 'controllers\api\CategoryController:getAll');
        $this->get('/search_paginate', 'controllers\api\CategoryController:getSearchPaginate');
        $this->post('/create', 'controllers\api\CategoryController:postCreate');
        $this->post('/delete', 'controllers\api\CategoryController:postDelete');
	});

    $this->group('/video', function()
    {
        $this->get('/all', 'controllers\api\VideoController:getAll');
        $this->get('/search_paginate', 'controllers\api\VideoController:getSearchPaginate');
        $this->post('/create', 'controllers\api\VideoController:postCreate');
        $this->post('/delete', 'controllers\api\VideoController:postDelete');
    });
	
	$this->group('/squad', function()
	{
	
	});
	
	$this->group('/team', function()
	{
        $this->get('/all', 'controllers\api\TeamController:getAll');
        $this->get('/search_paginate', 'controllers\api\TeamController:getSearchPaginate');
        $this->post('/create', 'controllers\api\TeamController:postCreate');
        $this->post('/delete', 'controllers\api\TeamController:postDelete');
	});
	
	$this->group('/tournament', function()
	{
        $this->get('/all', 'controllers\api\TournamentController:getAll');
        $this->get('/search_paginate', 'controllers\api\TournamentController:getSearchPaginate');
        $this->post('/create', 'controllers\api\TournamentController:postCreate');
        $this->post('/delete', 'controllers\api\TournamentController:postDelete');
	});
	
	$this->group('/comment', function()
	{
	
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