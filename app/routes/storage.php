<?php

use Intervention\Image\ImageManager as ImageManager;

use Slim\Http\Response;
use Slim\Http\Request;

use models\Country;
use models\SquadMember;
use models\User as User;
use models\Article as Article;
use models\EnemyTeam as EnemyTeam;
use models\Squad as Squad;
use models\Partner as Partner;
use models\GalleryImage as GalleryImage;
use models\ProductImage as ProductImage;

function modelImageResponse( Response $response, $path ) : Response
{
    //$type = getMimeType($path);
    //readfile($path);
    $ImageManager = new ImageManager(array('driver' => 'gd'));
    $img = $ImageManager->make($path);
    $response->write($img);
    //return $response->withHeader('Content-Type', $type)->withHeader('Content-Length', filesize($path));
    return $response->withStatus(200);
}

$app->get('/userProfilePicture/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = User::getRealImagePath($args['filename']);
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . User::NO_USER_IMAGE);
});

$app->get('/article_headline/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = Article::getRealImagePath($args['filename']);
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response,__NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

$app->get('/enemy_team_logo/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = EnemyTeam::getRealImagePath($args['filename']);
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_LOGO_IMAGE );
});

$app->get('/squad_logo/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = Squad::getRealImagePath($args['filename']);
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_LOGO_IMAGE );
});

$app->get('/squad_header_image/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = Squad::getRealImagePath($args['filename']);
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

$app->get('/squad_home_wallpaper/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = Squad::getRealImagePath($args['filename']);
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_HOME_WALLPAPER);
});


$app->get('/squadMemberHomeAvatar/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = SquadMember::getRealImagePath($args['filename']);
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
	return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_HOME_WALLPAPER);
});

$app->get('/requestPartnerLogo/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = Partner::getRealImagePath($args['filename']);
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

$app->get('/requestGalleryImage/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
	if(!empty($args['filename']))
	{
		$path = GalleryImage::getRealImagePath($args['filename']);
		if(isReadableFile($path)) return modelImageResponse($response, $path);
	}
	return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

$app->get('/requestProductImage/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
	if(!empty($args['filename']))
	{
		$path = ProductImage::getRealImagePath($args['filename']);
		if(isReadableFile($path)) return modelImageResponse($response, $path);
	}
	return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

// FLAGS

// S
$app->get('/requestSmallFlag/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = Country::getFlagPath($args['filename'], 'small');
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, Country::NO_SMALL );
});

// M
$app->get('/requestMediumFlag/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = Country::getFlagPath($args['filename'], 'medium');
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, Country::NO_MEDIUM );
});

// B
$app->get('/requestBigFlag/[{filename}]', function( Request $request, Response $response, $args ) : Response
{
    if(!empty($args['filename']))
    {
        $path = Country::getFlagPath($args['filename'], 'big');
        if(isReadableFile($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, Country::NO_BIG );
});
