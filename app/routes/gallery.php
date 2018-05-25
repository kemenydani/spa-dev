<?php

$app->group('/gallery', function ()
{
    $this->get('', 'controllers\GalleryController:index');
	$this->get('/{name}', 'controllers\GalleryController:getViewGallery');
    $this->get('/loadInfinityImages/', 'controllers\GalleryController:getLoadInfiniteImages');
});