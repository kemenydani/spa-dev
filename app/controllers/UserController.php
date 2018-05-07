<?php



namespace controllers;

use core\Auth;
use Intervention\Image\ImageManager as ImageManager;

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

use models\User as User;
use Slim\Http\UploadedFile;

class UserController extends ViewController
{

    public function index ( Request $request, Response $response, $args)
    {
        $user = User::find($args['username'], 'username');

        //if($user) $user = $user->getFormatted(User::PUBLIC_DATASET);

        $this->view->render($response, 'route.view.user.profile.html.twig', ['user' => $user]);
    }

    public function uploadPicture ( Request $request, Response $response, $args )
    {
	    $files = $request->getUploadedFiles();

        $User = Auth::user();

	    $ImageManager = new ImageManager(array('driver' => 'gd'));

        /* @var $uploadedFile UploadedFile */
	    $uploadedFile = $files['user_picture'];

	    if(!$uploadedFile) return false;

	    $img = $ImageManager->make($uploadedFile->getStream());

        $img->interlace(true);

	    if($img->getWidth() > 200 || $img->getHeight() > 200) $img->resize(200, 200);

	    $fileName =  md5($User->getProperty('username')) . '.' . $img->extension;

	    $img->save(User::IMAGE_PATH . DIRECTORY_SEPARATOR . $fileName );

	    $User->setProperty('profile_picture', $fileName);
        $User->save();

	    $img->encode('data-url');
	    $response->write($img->getEncoded());

        return $response->withStatus(200)->withHeader('Content-Type', $img->mime());
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