<?php

namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;

use models\Gallery;
use models\GalleryCollection;
use models\GalleryImageCollection;

use core\DB;

class GalleryController extends ViewController
{
    const INFINITE_LIMIT = 6;

    public function index ( Request $request, Response $response )
    {
        $galleries = new GalleryCollection(Gallery::getAll());

        $this->view->render($response, 'route.view.gallery.list.html.twig', ['galleries' => $galleries]);
    }

    public function getLoadInfiniteImages( Request $request, Response $response )
    {
        $galleryId = $request->getQueryParam('galleryId');
        $startAt   = $request->getQueryParam('startAt') ? $request->getQueryParam('startAt') : 0;

        $result = $this->getMoreImages($galleryId, $startAt);

        return $response->withStatus(200)->withJson($result);
    }

    protected function getMoreImages($gallery_id, $startAt = 0)
    {
        $params = [];

        $q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_gallery_image " .
              " WHERE gallery_id = " . $gallery_id .
              " ORDER BY id ASC " .
              " LIMIT ".static::INFINITE_LIMIT." OFFSET " . (int)$startAt
        ;

        /* @var \models\GalleryImageCollection $products */
        $collection = (GalleryImageCollection::queryToCollection($q1, $params));

        $total = DB::instance()->totalRowCount();

        return ['models' => $collection->getUrlArray() , 'total' => $total];
    }

    public function getViewGallery( Request $request, Response $response, $args )
    {
        $gallery = Gallery::find($args['name'], 'name');

	    $this->view->render($response, 'route.view.gallery.view2.html.twig', [
	        'gallery' => $gallery,
            'images' => $this->getMoreImages($gallery->getId()),
            'limit' => self::INFINITE_LIMIT,
        ]);
    }
}