<?php

namespace App\Helpers;

use App\Auth\Auth;
use Imagecraft\ImageBuilder;

/**
 * Class Image
 *
 * Helps resize and upload images to the server.
 * Ex: Profile picture upload
 *
 * @package App\Helpers
 */
class Image
{
    /**
     * Variables used for validation.
     *
     * @var int
     */
    private $MAX_FILE_SIZE = 2048; // 2MB

    protected $container;

    public function __construct()
    {
        $container = new \Slim\Container;
        $this->container = $container;
    }

    /**
     * Takes am image, resizes it, and uploads it to the server.
     *
     * @param $image
     * @param $file_name
     * @param $size
     */
    public function upload($image, $file_name, $size)
    {
        /* new file name */
        $path = $_SERVER['DOCUMENT_ROOT'] . $this->container->request->getUri()->getBasePath() . '/assets/uploads/avatars/' . $file_name;

        if ($image->getClientMediaType() == "image/gif" && Auth::user()->isSubscriber()) {
            $is_animated = true;
            $img = $this->resizeGif($image, $size);
        } else {
            $is_animated = false;
            $img = $this->resize($image, $size);
        }

        /* Save image */
        switch ($image->getClientMediaType()) {
            case 'image/jpeg':
                imagejpeg($img, $path, 100);
                break;

            case 'image/png':
                imagepng($img, $path);
                break;

            case 'image/gif':
                if ($is_animated) {
                    file_put_contents($path, $img);
                } else {
                    imagegif($img, $path);
                }

                break;

            default:
                exit;
                break;
        }

        /* cleanup memory */
        imagedestroy($img);
    }

    /**
     * Resize an image with provided temp file, file name, and a size.
     *
     * @param $image
     * @param $size
     */
    private function resize($image, $size)
    {
        /* Get original image x y */
        list($w, $h) = getimagesize($image->file);

        /* calculate new image size with ratio */
        $ratio = max($size/$w, $size/$h);
        $h = ceil($size / $ratio);
        $x = ($w - $size / $ratio) / 2;
        $w = ceil($size / $ratio);

        /* read binary data from image file */
        $imgString = file_get_contents($image->file);


        /* create image from string */
        $newImage = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($size, $size);
        imagealphablending($tmp, false);
        imagesavealpha($tmp, true); // Keep PNG transparency
        imagecopyresampled($tmp, $newImage, 0, 0, $x, 0, $size, $size, $w, $h);

        /* cleanup memory */
        imagedestroy($newImage);

        return $tmp;
    }

    /**
     * Resizes gif files so the gif does not lose its animation when resizing (if there is animation).
     *
     * @param $image
     * @param $size
     *
     * @return mixed
     */
    private function resizeGif($image, $size)
    {
        $gif = new ImageBuilder();

        $gif = $gif->addBackgroundLayer()
            ->filename($image->file)
            ->resize($size, $size, 'fill_crop')
            ->done()
            ->save();

        return $gif->getContents();
    }

    /**
     * Deletes a user's avatar.
     *
     * @param string $file
     */
    public function deleteAvatar($file)
    {
        unlink($_SERVER['DOCUMENT_ROOT'] . $this->container->request->getUri()->getBasePath() . '/assets/uploads/avatars/' . $file);
    }
}