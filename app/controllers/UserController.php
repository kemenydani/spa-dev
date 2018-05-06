<?php



namespace controllers;
use Intervention\Image\Image as Image;
use Intervention\Image\ImageManager as ImageManager;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\User as User;

class UserController extends ViewController
{

    public function index ( Request $request, Response $response, $args)
    {
        $user = User::find($args['username'], 'username');

        if($user) $user = $user->getFormatted(User::PUBLIC_DATASET);

        $this->view->render($response, 'route.view.user.profile.html.twig', ['user' => $user]);
    }

    public function uploadPicture ( Request $request, Response $response, $args )
    {
    	//$post = $request->getParsedBody();
	    $files = $request->getUploadedFiles();
	    
	    $ImageManager = new ImageManager(array('driver' => 'gd'));
	    
	    $f = $files['user_picture'];
	    
	    $img = $ImageManager->make($f->getStream());
	
	    //$o = json_decode($post['options']);
	    
	    //$img->resize($img->width() * $o->zoom, $img->height() * $o->zoom);
	    
	    //$img->crop($o->points[0], $o->points[1], $o->points[2], $o->points[3]);
	    
	    $img->save(__UPLOADS__ . '/images/user/' . md5('apple') . '.jpg');

        return $response->withStatus(200)->withJson(1);
    }

    public function uploadCover ( Request $request, Response $response, $args )
    {
        $files = $request->getUploadedFiles();

        if( array_key_exists('cover_picture', $files ) )
        {
            return FileUploadController::upload( $files['cover_picture'], 'images/users/16/cover_picture' );
        }
        return $response->withStatus(404, 'Invalid File Key');
    }

}