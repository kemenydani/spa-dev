<?php

$app->group('/api', function ()
{
    $this->group('/user', function()
    {
        $this->get('/all', 'controllers\api\UserController:getUsers');
            //->add( new \middlewares\RequiresAuth())
            //->add( new \middlewares\RequiresRole(['users.list']));

        //$this->get('/{id}', 'controllers\api\UserController:getUser');
        $this->get('/auth', 'controllers\api\UserController:getAuth');
        $this->post('/auth', 'controllers\api\UserController:postAuth');
        $this->post('/register', 'controllers\api\UserController:postRegister');
        $this->post('/login', 'controllers\api\UserController:postLogin');
	    $this->post('/logout', 'controllers\api\UserController:postLogout');
    });
	
	$this->group('/article', function()
	{
		$this->get('/all', 'controllers\api\ArticleController:getAll');
		$this->post('/create', 'controllers\api\ArticleController:postCreate');
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