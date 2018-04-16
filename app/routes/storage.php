<?php

use Slim\Http\Response;
use models\User as User;
use models\Article as Article;
use models\EnemyTeam as EnemyTeam;
use models\Squad as Squad;

function modelImageResponse(Response $response, $path)
{
    $type = mime_content_type($path);
    readfile($path);
    return $response->withHeader('Content-Type', $type)->withHeader('Content-Length', filesize($path));
}

$app->get('/profile_picture/{filename}', function( $request, $response, $args )
{
    $path = User::getRealImagePath($args['filename']);

    if($path) return modelImageResponse($response, $path);

    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . User::NO_USER_IMAGE);
});

$app->get('/article_headline/{filename}', function( $request, $response, $args )
{
    $path = Article::getRealImagePath($args['filename']);

    if($path) return modelImageResponse($response, $path);

    return modelImageResponse($response, '');
});

$app->get('/enemy_logo/{filename}', function( $request, $response, $args )
{
    $path = EnemyTeam::getRealImagePath($args['filename']);

    if($path) return modelImageResponse($response, $path);

    return modelImageResponse($response, '');
});

$app->get('/squad_logo/{filename}', function( $request, $response, $args )
{
    $path = Squad::getRealImagePath($args['filename']);

    if($path !== null) return modelImageResponse($response, $path);

    return modelImageResponse($response, __NOIMAGE__ . DIRECTORY_SEPARATOR . Squad::NO_LOGO_IMAGE );
});

$app->get('/squad_header/{filename}', function( $request, $response, $args )
{
    $path = Squad::getRealImagePath($args['filename']);

    if($path) return modelImageResponse($response, $path);

    return modelImageResponse($response, '');
});
