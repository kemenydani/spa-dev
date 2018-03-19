<?php

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/uploadImage', 'controllers\FileUploadController:uploadImage');
