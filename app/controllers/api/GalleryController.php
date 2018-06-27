<?php

namespace controllers\api;

use Intervention\Image\ImageManager;
use models\GalleryImage;
use Slim\Http\Request;
use Slim\Http\Response;

use Slim\Http\UploadedFile;

use models\Gallery as Gallery;
use core\Auth as Auth;
use core\DB as DB;

class GalleryController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new Gallery() );
    }

    public function postUploadImage( Request $request, Response $response ) : Response
    {
	    $files = $request->getUploadedFiles();
	    $id = $request->getQueryParam('id');
	    
	    $ImageManager = new ImageManager(array('driver' => 'gd'));
	
	    /* @var $uploadedFile UploadedFile */
	    $uploadedFile = $files['image'];
	
	    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
	    $basename = basename($uploadedFile->getClientFilename());
	    
	    //TODO:: pathinfo bug, fix: double fake extansion needed
	    $fileName = 'gallery_' . md5($basename) . ".gallery.jpg";
	    
	    $img = $ImageManager->make($uploadedFile->getStream());
	    
	    $img->interlace(true);
	
	    $path = GalleryImage::IMAGE_PATH . DIRECTORY_SEPARATOR . $fileName;
		   
	    $img->encode('jpg');
	    
	    $img->save((string)$path);
	    
	    $GalleryImage = GalleryImage::create([
		    'gallery_id' => $id,
		    'file_name' => $fileName
	    ]);
	    
	    $GalleryImage->save();
	    
        return $response->withStatus(200);
    }

    public function postCreate( Request $request, Response $response ) : Response
	{
		$data = $request->getParsedBody();
		
		$title      = isset($data['title'])      ? $data['title']      : '';
		$teaser     = isset($data['teaser'])     ? $data['teaser']     : '';
		$content    = isset($data['content'])    ? $data['content']    : '';
		$categories = isset($data['categories']) ? $data['categories'] : '';
		
		$Gallery = Gallery::create([
			'title'      => $title,
			'teaser'     => $teaser,
			'content'    => $content,
			'created_by' => Auth::user()->getId()
		]);

		$id = $Gallery->save();
		
		$category_ids = [];
		
		foreach( $categories as $key => $value )
		{
			$category_ids[] = $value['id'];
		}
		

		if( $id === false ) return $response->withStatus(500, 'Database error: Could not insert article.');
		
		return $response->withJson( $Gallery->getProperties() )->withStatus(200);
	}

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items)
        {
            $ImageManager = new ImageManager(array('driver' => 'gd'));

            $q = "SELECT * FROM _xyz_gallery_image WHERE gallery_id = ? ";

            foreach($items as &$gallery)
            {
                $Images = GalleryImage::findAll($gallery['id'], 'gallery_id');

                $urls = [];
                /* @var \models\GalleryImage $Image */
                //if(is_array($Images)) foreach($Images as $Image) $urls[] = 'http://phpapp' . (string)$Image->requestImageUrl();


                    foreach($Images as $Image) {
                        $path = $Image::IMAGE_PATH . DIRECTORY_SEPARATOR . $Image->getFileName();
                        $img = $ImageManager->make($path);
                        $urls[] = ['id' => $Image->getId(), 'dataUrl' => $img->encode('data-url')];
                    }


                $gallery['images'] = $urls;
            }

            return $items;
        });
    }



}