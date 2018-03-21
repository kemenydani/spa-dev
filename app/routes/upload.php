<?php

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/uploadImage', 'controllers\FileUploadController:uploadImage');

$app->post('/uploadUserPicture', 'controllers\UserController:uploadPicture');
$app->post('/uploadUserCover', 'controllers\UserController:uploadCover');
