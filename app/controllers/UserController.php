<?php

namespace controllers;

use Intervention\Image\ImageManager as ImageManager;

use models\UserProfile;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

use models\User as User;
use Slim\Http\UploadedFile;

use models\Country;

use core\Auth;

class UserController extends ViewController
{
    public function postUpdateProfile(Request $request, Response $response)
    {
        $id = $request->getQueryParam('userId');

        $AuthUser = Auth::user();

        if(!$AuthUser || (int)$AuthUser->getId() !== (int)$id) return $response->withStatus(401, 'Unauthorized');

        $body = $request->getParsedBody();
        $errors = [];

        $userSet = [];
        $profileSet = [];

        $formUserData = $body['user'];
        $formProfileData = $body['profile'];

        unset($formProfileData['user_id']);
        unset($formProfileData['id']);

        if(filter_var($formUserData['email'], FILTER_VALIDATE_EMAIL))
        {
            $emailFree = true;

            if(strcmp($formUserData['email'], $AuthUser->getProperty('email')) !== 0)
            {
                $EmailUser = User::find($formUserData['email'], 'email');

                if($EmailUser && $EmailUser->getId() !== $AuthUser->getId())
                {
                    $emailFree = false;
                }
            }

            if($emailFree)
            {
                $userSet['email'] = $formUserData['email'];
            }
            else
            {
                $errors['email'] = 'Email is already in use';
            }
        }
        else
        {
            $errors['email'] ='Invalid email format';
        }

        if($formUserData['country_name'] && $formUserData['country_code'])
        {
            $country = Country::find($formUserData['country_code']);

            if($country)
            {
                $userSet['country_name'] = $formUserData['country_name'];
                $userSet['country_code'] = $formUserData['country_code'];
            }
        }

        /* @var UserProfile $UserProfile */
        $UserProfile = $AuthUser->getUserProfile();

        foreach($formProfileData as $column => $value) $profileSet[$column] = $value;

        if(!count($errors))
        {
            $AuthUser->setProperties($userSet);
            $UserProfile->setProperties($profileSet);
            $AuthUser->save();
            $UserProfile->save();

            $model = $this->generateProfileModelForUser($AuthUser);

            return $response->withStatus(200)->withJson(['error' => $errors, 'model' => $model]);
        }
        else
        {
            return $response->withStatus(200)->withJson(['error' => $errors]);
        }
    }

    public function generateProfileModelForUser(User $user = null)
    {
        if(!$user) return false;

        return $model =
            [
                'user' => $user->getFormatted( User::PUBLIC_DATASET ),
                'profile' => $user->getUserProfile()->getFormatted(),
                'comments' => $user->getLastComments(),
            ]
        ;
    }

    public function index ( Request $request, Response $response, $args)
    {
        $user = User::find($args['username'], 'username');

        $model = $this->generateProfileModelForUser($user);

        $this->view->render($response, 'route.view.user.profile.html.twig', [
                'model' => $model,
            ]
        );
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

	    $fileName =  md5($User->getProperty('username')) . '.jpg';

	    $img->encode('jpg');

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