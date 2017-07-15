<?php

namespace App\Controllers\Auth\Account;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use App\Helpers\Image;


class PictureSettingsController extends Controller
{
    public function getPictureSettings($request, $response)
    {
        $md5_email = md5($this->auth->user()->email);

        $data = ['md5_email' => $md5_email];
        return $this->view->render($response, 'account/picture.twig', $data);
    }

    public function postPictureSettings($request, $response)
    {

        $validation = $this->validator->validate($request, [
            'avatar' => v::image()->size(NULL, '2MB')
        ], true);


        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('account.picture'));
        }

        $tmp_img = $request->getUploadedFiles()['avatar'];
        $img_ext = pathinfo($request->getUploadedFiles()['avatar']->getClientFilename(), PATHINFO_EXTENSION);
        $file_name = $this->auth->user()->id . "-" . mt_rand() . "." . $img_ext;

        $image = new Image;

        // REMOVE OLD AVATAR IF EXISTS
        if($this->auth->user()->uploaded_avatar) {
            $image->deleteAvatar($this->auth->user()->uploaded_avatar);
        }

        $image->upload($tmp_img, $file_name, 350);


        $this->auth->user()->update([
            'gravatar' => false,
            'uploaded_avatar' => $file_name
        ]);

        $this->flash->addMessage('global_success', 'Your profile picture have been updated');
        return $response->withRedirect($this->router->pathFor('account.picture'));
    }

    public function deletePicture($request, $response)
    {
        if($this->auth->user()->uploaded_avatar) {
            $image = new Image();
            $image->deleteAvatar($this->auth->user()->uploaded_avatar);

            $this->auth->user()->update([
                'gravatar' => true,
                'uploaded_avatar' => NULL
            ]);

            $this->flash->addMessage('global_success', 'Your profile picture have been deleted');
            return $response->withRedirect($this->router->pathFor('account.picture'));
        } else {
            $this->flash->addMessage('global_notice', 'You do not have an uploaded avatar to delete');
            return $response->withRedirect($this->router->pathFor('account.picture'));
        }
    }

    public function changePicture($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'switch_avatar' => v::numeric()->between(0,1)
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('account.picture'));
        }

        $this->auth->user()->update([
            'gravatar' => $request->getParam('switch_avatar')
        ]);

        $this->flash->addMessage('global_success', 'Your profile picture have been updated');
        return $response->withRedirect($this->router->pathFor('account.picture'));
    }

}