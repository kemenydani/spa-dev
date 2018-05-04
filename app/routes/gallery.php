<?php

$app->group('/gallery', function ()
{
    $this->get('', 'controllers\GalleryController:index');
	$this->get('/{name}', 'controllers\GalleryController:getViewGallery');
});