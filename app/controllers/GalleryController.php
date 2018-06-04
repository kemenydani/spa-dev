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
    const INFINITE_LIMIT_GALLERIES = 6;
    const INFINITE_LIMIT_IMAGES = 9;

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

    // Gallery view image-infinite-scroll
    protected function getMoreImages($gallery_id, $startAt = 0)
    {
        $q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_gallery_image " .
              " WHERE gallery_id = :gallery_id " .
              " ORDER BY id ASC " .
              " LIMIT ".static::INFINITE_LIMIT_IMAGES." OFFSET " . (int)$startAt
        ;

        /* @var \models\GalleryImageCollection $collection */
        $collection = (GalleryImageCollection::queryToCollection($q1, ['gallery_id' => $gallery_id]));

        $total = DB::instance()->totalRowCount();

        return [
            'urls' => $collection->getUrlArray() ,
            'totalItems' => $total
        ];
    }

    public function getViewGallery( Request $request, Response $response, $args )
    {
        $gallery = Gallery::find($args['name'], 'name');

        $data = $this->getMoreImages($gallery->getId());

        $randomImageUrl = null;

        if(is_array($data['urls']))
        {
            $randId = array_rand($data['urls'], 1);
            $randomImageUrl = $data['urls'][$randId];
        }

	    $this->view->render($response, 'route.view.gallery.view2.html.twig', [
	        'headImageUrl' => $randomImageUrl,
	        'gallery' => $gallery,
            'data' => $data,
            'limit' => self::INFINITE_LIMIT_IMAGES,
        ]);
    }

    // Gallery list gallery-infinite-scroll
    protected function getMoreGalleries($startAt = 0)
    {
        $params = [];

        $q1 = " SELECT SQL_CALC_FOUND_ROWS * FROM _xyz_gallery " .
              " ORDER BY id ASC " .
              " LIMIT ".static::INFINITE_LIMIT_GALLERIES." OFFSET " . (int)$startAt;


        /* @var \models\GalleryCollection $collection */
        $collection = (GalleryCollection::queryToCollection($q1, $params));

        $totalItems = DB::instance()->totalRowCount();

        return [
            'galleries' => $collection->getProperties() ,
            'totalItems' => $totalItems
        ];
    }
}