<?php

use Slim\Http\Request;
use Slim\Http\Response;

use Intervention\Image\ImageManager;
use controllers\api\ImageUploadController;

use models\Article;
use models\Gallery;
use models\GalleryImage;
use models\Partner;
use models\Product;
use models\ProductImage;
use models\Squad;
use models\SquadMember;
use models\EnemyTeam;

$app->group('/api', function ()
{
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
        $this->get('/findAll', 'controllers\api\UserController:findAll');
        $this->get('/likeAll', 'controllers\api\UserController:likeAll');
        $this->post('/store', 'controllers\api\UserController:postStore');
    });
	
	$this->group('/article', function()
	{
		$this->get('/all', 'controllers\api\ArticleController:getAll');
        $this->get('/search_paginate', 'controllers\api\ArticleController:getSearchPaginate');
		$this->post('/create', 'controllers\api\ArticleController:postCreate');
        $this->post('/delete', 'controllers\api\ArticleController:postDelete');
        $this->post('/activate', 'controllers\api\ArticleController:postActivate');
        $this->post('/deactivate', 'controllers\api\ArticleController:postDeactivate');
		$this->post('/store', 'controllers\api\ArticleController:postStore');

        $this->post('/uploadArticleImage', function(Request $request, Response $response)
        {
            $id = $request->getQueryParam('id');
            /* @var Article $Article */
            $Article = Article::find($id);

            ImageUploadController::$unique = true;

            $oldPath = Article::IMAGE_PATH . DIRECTORY_SEPARATOR . $Article->getHeadlineImage();

            return ImageUploadController::upload($request, $response, Article::IMAGE_PATH, function($fileName) use ($Article, $oldPath)
            {
                if(isWritableFile($oldPath) && isReadableFile($oldPath)) unlink(realpath($oldPath));

                $Article->setProperty('headline_image', $fileName);
                $Article->save();
            });
        });
	});

    $this->group('/gallery', function()
    {
        $this->get('/all', 'controllers\api\GalleryController:getAll');
        $this->get('/search_paginate', 'controllers\api\GalleryController:getSearchPaginate');
        $this->post('/create', 'controllers\api\GalleryController:postCreate');
        $this->post('/delete', 'controllers\api\GalleryController:postDelete');
        $this->post('/activate', 'controllers\api\GalleryController:postActivate');
        $this->post('/deactivate', 'controllers\api\GalleryController:postDeactivate');
	    $this->post('/store', 'controllers\api\GalleryController:postStore');
        $this->get('/fetchImages', 'controllers\api\GalleryController:fetchImages');
        $this->post('/uploadGalleryImage', function(Request $request, Response $response)
        {
            $id = $request->getQueryParam('id');
            /* @var Gallery $Gallery */
            $Gallery = Gallery::find($id);

            ImageUploadController::$unique = true;

            return ImageUploadController::upload($request, $response, GalleryImage::IMAGE_PATH, function($fileName) use ($Gallery)
            {
                $GalleryImage = GalleryImage::create([
                    'file_name'  => $fileName,
                    'gallery_id' => $Gallery->getProperty('id'),
                ]);

                $GalleryImage->save();
            });
        });
    });

    $this->group('/gallery_image', function()
    {
        $this->get('/all', 'controllers\api\GalleryImageController:getAll');
        $this->get('/findAll', 'controllers\api\GalleryImageController:findAll');
        $this->post('/delete', 'controllers\api\GalleryImageController:postDelete');
    });

    $this->group('/product', function()
    {
        $this->get('/all', 'controllers\api\ProductController:getAll');
        $this->get('/search_paginate', 'controllers\api\ProductController:getSearchPaginate');
        $this->post('/create', 'controllers\api\ProductController:postCreate');
        $this->post('/delete', 'controllers\api\ProductController:postDelete');
        $this->post('/activate', 'controllers\api\ProductController:postActivate');
        $this->post('/deactivate', 'controllers\api\ProductController:postDeactivate');
        $this->post('/store', 'controllers\api\ProductController:postStore');
        $this->get('/fetchImages', 'controllers\api\ProductController:fetchImages');

        $this->post('/uploadProductImage', function(Request $request, Response $response)
        {
            $id = $request->getQueryParam('id');
            /* @var Product $Product */
            $Product = Product::find($id);

            ImageUploadController::$unique = true;
            ImageUploadController::$format = 'png';

            return ImageUploadController::upload($request, $response, ProductImage::IMAGE_PATH, function($fileName) use ($Product)
            {
                $ProductImage = ProductImage::create([
                    'file_name' => $fileName,
                    'product_id' => $Product->getProperty('id'),
                ]);

                $ProductImage->save();
            });
        });
    });

    $this->group('/product_image', function()
    {
        $this->get('/all', 'controllers\api\ProductImageController:getAll');
        $this->get('/findAll', 'controllers\api\ProductImageController:findAll');
        $this->post('/delete', 'controllers\api\ProductImageController:postDelete');
    });

    $this->group('/partner', function()
    {
        $this->get('/all', 'controllers\api\PartnerController:getAll');
        $this->get('/search_paginate', 'controllers\api\PartnerController:getSearchPaginate');
        $this->post('/create', 'controllers\api\PartnerController:postCreate');
        $this->post('/delete', 'controllers\api\PartnerController:postDelete');
        $this->post('/activate', 'controllers\api\PartnerController:postActivate');
        $this->post('/deactivate', 'controllers\api\PartnerController:postDeactivate');
        $this->post('/uploadImage', 'controllers\api\PartnerController:postUploadImage');
        $this->post('/store', 'controllers\api\PartnerController:postStore');

        $this->post('/uploadPartnerImage', function(Request $request, Response $response)
        {
            $id = $request->getQueryParam('id');
            /* @var Partner $Partner */
            $Partner = Partner::find($id);

            ImageUploadController::$unique = true;

            $oldPath = Partner::IMAGE_PATH . DIRECTORY_SEPARATOR . $Partner->getLogo();

            return ImageUploadController::upload($request, $response, Partner::IMAGE_PATH, function($fileName) use ($Partner, $oldPath)
            {
                if(isWritableFile($oldPath) && isReadableFile($oldPath)) unlink(realpath($oldPath));

                $Partner->setProperty('logo', $fileName);
                $Partner->save();
            });
        });
    });

    $this->group('/squad', function()
    {
        $this->get('/all', 'controllers\api\SquadController:getAll');
        $this->get('/search_paginate', 'controllers\api\SquadController:getSearchPaginate');
        $this->post('/create', 'controllers\api\SquadController:postCreate');
        $this->post('/delete', 'controllers\api\SquadController:postDelete');
        $this->post('/activate', 'controllers\api\SquadController:postActivate');
        $this->post('/deactivate', 'controllers\api\SquadController:postDeactivate');
	    $this->post('/store', 'controllers\api\SquadController:postStore');
	    $this->get('/findAll', 'controllers\api\SquadController:findAll');
	    $this->get('/likeAll', 'controllers\api\SquadController:likeAll');

        $this->post('/uploadSquadImage', function(Request $request, Response $response)
        {
            $id = $request->getQueryParam('id');
            /* @var Squad $Squad */
            $Squad = Squad::find($id);

            ImageUploadController::$unique = true;

            $oldPath = Squad::IMAGE_PATH . DIRECTORY_SEPARATOR . $Squad->getHeaderImage();

            return ImageUploadController::upload($request, $response, Squad::IMAGE_PATH, function($fileName) use ($Squad, $oldPath)
            {
                if(isWritableFile($oldPath) && isReadableFile($oldPath)) unlink(realpath($oldPath));

                $Squad->setProperty('header_image', $fileName);
                $Squad->save();
            });
        });
    });

    $this->group('/squad_member', function()
    {
        $this->get('/all', 'controllers\api\SquadMemberController:getAll');
        $this->get('/search_paginate', 'controllers\api\SquadMemberController:getSearchPaginate');
        $this->post('/create', 'controllers\api\SquadMemberController:postCreate');
        $this->post('/delete', 'controllers\api\SquadMemberController:postDelete');
        $this->post('/activate', 'controllers\api\SquadMemberController:postActivate');
        $this->post('/deactivate', 'controllers\api\SquadMemberController:postDeactivate');
        $this->post('/store', 'controllers\api\SquadMemberController:postStore');

        $this->post('/uploadSquadMemberAvatar', function(Request $request, Response $response)
        {
            $id = $request->getQueryParam('id');
            /* @var SquadMember $SquadMember */
            $SquadMember = SquadMember::find($id);

            ImageUploadController::$unique = true;

            $oldPath = SquadMember::IMAGE_PATH . DIRECTORY_SEPARATOR . $SquadMember->getHomeAvatar();

            return ImageUploadController::upload($request, $response, SquadMember::IMAGE_PATH, function($fileName) use ($SquadMember, $oldPath)
            {
                if(isWritableFile($oldPath) && isReadableFile($oldPath)) unlink(realpath($oldPath));

                $SquadMember->setProperty('home_avatar', $fileName);
                $SquadMember->save();
            });
        });
    });

    $this->group('/paypal', function()
    {
        $this->get('/all', 'controllers\api\PayPalController:getAll');
        $this->get('/search_paginate', 'controllers\api\PayPalController:getSearchPaginate');
        $this->post('/create', 'controllers\api\ProductController:postCreate');
        $this->post('/delete', 'controllers\api\ProductController:postDelete');
    });

    $this->group('/event', function()
    {
        $this->get('/all', 'controllers\api\EventController:getAll');
        $this->get('/search_paginate', 'controllers\api\EventController:getSearchPaginate');
        $this->post('/create', 'controllers\api\EventController:postCreate');
        $this->post('/delete', 'controllers\api\EventController:postDelete');
        $this->post('/activate', 'controllers\api\EventController:postActivate');
        $this->post('/deactivate', 'controllers\api\EventController:postDeactivate');
        $this->post('/store', 'controllers\api\EventController:postStore');
    });

    $this->group('/award', function()
    {
        $this->get('/all', 'controllers\api\AwardController:getAll');
        $this->get('/search_paginate', 'controllers\api\AwardController:getSearchPaginate');
        $this->post('/create', 'controllers\api\AwardController:postCreate');
        $this->post('/delete', 'controllers\api\AwardController:postDelete');
        $this->post('/activate', 'controllers\api\AwardController:postActivate');
        $this->post('/deactivate', 'controllers\api\AwardController:postDeactivate');
	    $this->post('/store', 'controllers\api\AwardController:postStore');
    });

    $this->group('/match', function()
    {
        $this->get('/all', 'controllers\api\MatchController:getAll');
        $this->get('/search_paginate', 'controllers\api\MatchController:getSearchPaginate');
        $this->post('/create', 'controllers\api\MatchController:postCreate');
        $this->post('/delete', 'controllers\api\MatchController:postDelete');
	    $this->post('/store', 'controllers\api\MatchController:postStore');
    });

    $this->group('/enemy', function()
    {
        $this->get('/all', 'controllers\api\EnemyController:getAll');
        $this->get('/search_paginate', 'controllers\api\EnemyController:getSearchPaginate');
        $this->post('/create', 'controllers\api\EnemyController:postCreate');
        $this->post('/delete', 'controllers\api\EnemyController:postDelete');

        $this->get('/findAll', 'controllers\api\EnemyController:findAll');
        $this->get('/likeAll', 'controllers\api\EnemyController:likeAll');
	    $this->post('/store', 'controllers\api\EnemyController:postStore');

        $this->post('/uploadEnemyTeamLogo', function(Request $request, Response $response)
        {
            $id = $request->getQueryParam('id');
            /* @var EnemyTeam $EnemyTeam */
            $EnemyTeam = EnemyTeam::find($id);

            ImageUploadController::$unique = true;

            $oldPath = EnemyTeam::IMAGE_PATH . DIRECTORY_SEPARATOR . $EnemyTeam->getLogo();

            return ImageUploadController::upload($request, $response, EnemyTeam::IMAGE_PATH, function($fileName) use ($EnemyTeam, $oldPath)
            {
                if(isWritableFile($oldPath) && isReadableFile($oldPath)) unlink(realpath($oldPath));

                $EnemyTeam->setProperty('logo', $fileName);
                $EnemyTeam->save();
            });
        });
    });

    $this->group('/setting', function()
    {
        $this->get('/fetchSettings', 'controllers\api\SettingController:getFetchSettings');
        $this->post('/updateSetting', 'controllers\api\SettingController:postUpdateSetting');
    });

    $this->group('/context', function()
    {
        $this->get('/all', 'controllers\api\ContextController:getAll');
        $this->get('/search_paginate', 'controllers\api\ContextController:getSearchPaginate');
        $this->post('/create', 'controllers\api\ContextController:postCreate');
        $this->post('/delete', 'controllers\api\ContextController:postDelete');
        $this->post('/store', 'controllers\api\ContextController:postStore');
        $this->get('/findAll', 'controllers\api\ContextController:findAll');
        $this->get('/likeAll', 'controllers\api\ContextController:likeAll');
    });

	$this->group('/category', function()
	{
        $this->get('/all', 'controllers\api\CategoryController:getAll');
        $this->get('/search_paginate', 'controllers\api\CategoryController:getSearchPaginate');
        $this->post('/create', 'controllers\api\CategoryController:postCreate');
        $this->post('/delete', 'controllers\api\CategoryController:postDelete');
		$this->post('/store', 'controllers\api\CategoryController:postStore');
		$this->get('/findAll', 'controllers\api\CategoryController:findAll');
		$this->get('/likeAll', 'controllers\api\CategoryController:likeAll');
	});

    $this->group('/country', function()
    {
        $this->get('/all', 'controllers\api\CategoryController:getAll');
        $this->get('/findAll', 'controllers\api\CountryController:findAll');
        $this->get('/likeAll', 'controllers\api\CountryController:likeAll');
    });

    $this->group('/comment', function()
    {
        $this->get('/all', 'controllers\api\CommentController:getAll');
        $this->get('/search_paginate', 'controllers\api\CommentController:getSearchPaginate');
        $this->post('/delete', 'controllers\api\CommentController:postDelete');
    });

})->add(function(Request $request, Response $response, $next)
{
    /* @var Response $response */
	$response = $next( $request, $response );
	
    return $response
        ->withHeader( 'Access-Control-Allow-Origin', '*')
        ->withHeader( 'Access-Control-Allow-Credentials', 'true')
        ->withHeader( 'Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization' )
        ->withHeader( 'Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS' );

});