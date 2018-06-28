<?php

use models\Country;
use Slim\Http\Response;
use models\User as User;
use models\Article as Article;
use models\EnemyTeam as EnemyTeam;
use models\Squad as Squad;
use models\Partner as Partner;
use models\GalleryImage as GalleryImage;
use models\ProductImage as ProductImage;

use Intervention\Image\ImageManager as ImageManager;

function modelImageResponse(Response $response, $path)
{
    $type = mime_content_type($path);
    readfile($path);
    return $response->withHeader('Content-Type', $type)->withHeader('Content-Length', filesize($path));
}

$app->get('/userProfilePicture/[{filename}]', function( $request, $response, $args )
{
    $path = User::getRealImagePath($args['filename']);

    if($path) return modelImageResponse($response, $path);

    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . User::NO_USER_IMAGE);
});



$app->get('/article_headline/[{filename}]', function( $request, $response, $args )
{
    $path = Article::getRealImagePath($args['filename']);

    if($path) return modelImageResponse($response, $path);

    return modelImageResponse($response,__NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

$app->get('/enemy_team_logo/[{filename}]', function( $request, $response, $args )
{
    $path = EnemyTeam::getRealImagePath($args['filename']);

    if($path) return modelImageResponse($response, $path);

    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_LOGO_IMAGE );
});

$app->get('/squad_logo/[{filename}]', function( $request, $response, $args )
{
    $path = Squad::getRealImagePath($args['filename']);

    if($path !== null) return modelImageResponse($response, $path);

    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_LOGO_IMAGE );
});

$app->get('/squad_header_image/[{filename}]', function( $request, $response, $args )
{
    if(!empty($args['filename']))
    {
        $path = Squad::getRealImagePath($args['filename']);
        if (file_exists($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

$app->get('/squad_home_wallpaper/[{filename}]', function( $request, $response, $args )
{
    $path = Squad::getRealImagePath($args['filename']);

    if($path) return modelImageResponse($response, $path);

    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_HOME_WALLPAPER);
});


$app->get('/squadMemberHomeAvatar/[{filename}]', function( $request, $response, $args )
{
	$path = Squad::getRealImagePath($args['filename']);
	
	if($path) return modelImageResponse($response, $path);
	
	return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_HOME_WALLPAPER);
});

$app->get('/requestPartnerLogo/[{filename}]', function( $request, $response, $args )
{
    if(!empty($args['filename']))
    {
        $path = Partner::getRealImagePath($args['filename']);
        if(file_exists($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

$app->get('/requestGalleryImage/[{filename}]', function( $request, $response, $args )
{
	if(!empty($args['filename']))
	{
		$path = GalleryImage::getRealImagePath($args['filename']);
		if(file_exists($path)) return modelImageResponse($response, $path);
	}
	return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

$app->get('/requestProductImage/[{filename}]', function( $request, $response, $args )
{
	if(!empty($args['filename']))
	{
		$path = ProductImage::getRealImagePath($args['filename']);
		if(file_exists($path)) return modelImageResponse($response, $path);
	}
	return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . 'no-image-grey-cross.jpg' );
});

// FLAGS

// S
$app->get('/requestSmallFlag/[{filename}]', function( $request, $response, $args )
{
    if(!empty($args['filename']))
    {
        $path = Country::getFlagPath($args['filename'], 'small');
        if(file_exists($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, Country::NO_SMALL );
});

// M
$app->get('/requestMediumFlag/[{filename}]', function( $request, $response, $args )
{
    if(!empty($args['filename']))
    {
        $path = Country::getFlagPath($args['filename'], 'medium');
        if(file_exists($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, Country::NO_MEDIUM );
});

// B
$app->get('/requestBigFlag/[{filename}]', function( $request, $response, $args )
{
    if(!empty($args['filename']))
    {
        $path = Country::getFlagPath($args['filename'], 'big');
        if(file_exists($path)) return modelImageResponse($response, $path);
    }
    return modelImageResponse($response, Country::NO_BIG );
});

