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

    public function postDelete(Request $request, Response $response, $beforeDelete = null, $afterDelete = null): Response
    {
        return parent::postDelete($request, $response, function($range)
        {
            foreach($range as $galleryId)
            {
                $GalleryImages = GalleryImage::findAll($galleryId, 'gallery_id');
                /* @var GalleryImage $GalleryImage */
                foreach($GalleryImages as $GalleryImage)
                {
                    $path = GalleryImage::IMAGE_PATH . DIRECTORY_SEPARATOR . $GalleryImage->getFileName();

                    if(isReadableFile($path) && isReadableFile($path)) unlink(realpath($path));

                    $GalleryImage->destroy();
                }
            }
        }, $afterDelete);
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

    public function fetchImages(Request $request, Response $response, $args = [])
    {
        $id = $request->getQueryParam('id');

        $GalleryImages = GalleryImage::findAll($id, 'gallery_id');

        if(!$GalleryImages) $GalleryImages = [];

        $res = [];

        /* @var GalleryImage $Image */
        foreach ($GalleryImages as $Image)
        {
            $res[] = [
                'id' => $Image->getId(),
                'file_name' => $Image->getFileName(),
                'path' => $Image->requestImageUrl()
            ];
        }

        return $response->withJson($res)->withStatus(200);
    }

    public function getSearchPaginate( Request $request, Response $response, $args = [], $joinModels = []) : Response
    {
        return parent::getSearchPaginate($request, $response, [], function($items)
        {
            $ImageManager = new ImageManager(array('driver' => 'gd'));

            foreach($items as &$gallery)
            {
                $Images = GalleryImage::findAll($gallery['id'], 'gallery_id');

                $urls = [];

                /* @var \models\GalleryImage $GalleryImage */
                foreach($Images as $GalleryImage)
                {
                    /*
                    $path = GalleryImage::IMAGE_PATH . DIRECTORY_SEPARATOR .$GalleryImage->getFileName();

                    if(!isReadableFile($path)) continue;

                    $img = $ImageManager->make($path);
                    $img = $img->encode('data-url');
                    $encoded = $img->getEncoded();
                    $urls[] = ['file_name' => $GalleryImage->getFileName(), 'encoded' => $encoded];
                    */
                }

                $gallery['images'] = $urls;
            }

            return $items;
        });
    }

}
