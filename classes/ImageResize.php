<?php

namespace classes;

/**
 * Create a destination image, get the source image
 */

class ImageResize
{

    //The name of the target image
    private $tar_image;

    //The destination path without the file name
    public $dst_path;
    //The path of the source image
    public $src;

    //The path of the destination image
    public $dst;

    //The mime type of the Image
    private $image_type;

    //Height of the source image
    private $src_h;

    //Width of the source image
    private $src_w;

    //Height of the destination image
    public $dst_h = 100;

    //Width of the destination image
    public $dst_w = 100;

    //Source image X coordinate
    private $src_x = 0;

    //Source image y coordinate
    private $src_y = 0;

    //Destination image x coordinate
    private $dst_x = 0;

    //Destination image y coordinate
    private $dst_y = 0;

    //The class constructor
    public function __construct(
        //Gets only the name of the image not the path
        string $target,
        //Gets the destination path not the file name
        string $dstination
    ) {
        $this->tar_image = $target;
        $this->src = "C:\\xampp\\htdocs\\rs_book\\files\\" . $this->tar_image;
        $this->image_type = mime_content_type($this->src);
        $this->dst_path = $dstination;
        if ($this->image_type === "image/jpeg" || $this->image_type === "image/png") {
            list($this->src_w, $this->src_h) = \getimagesize($this->src);
        }
    }

    //Create an thumbnail
    public function thumbNail(): bool
    {

        //Create an blank image for thumbnail
        $blank_image = \imagecreatetruecolor($this->dst_w, $this->dst_h);

        //Create an image resource from the sourec image

        //when the sourec image type is JPEG
        if ($this->image_type === "image/jpeg") {
            $image = imagecreatefromjpeg($this->src);
        }
        //When the source image type is png
        if ($this->image_type === "image/png") {
            $image = \imagecreatefrompng($this->src);
        }

        if (!empty($blank_image) && !empty($image)) {
            \imagecopyresampled(
                $blank_image,
                $image,
                $this->dst_x,
                $this->dst_y,
                $this->src_x,
                $this->src_y,
                $this->dst_w,
                $this->dst_h,
                $this->src_w,
                $this->src_h
            );

            //Location where the new image will be saved
            $save_to = $this->dst_path . '/' . $this->tar_image;

            switch ($this->image_type) {
                case 'image/jpeg':
                    if (imagejpeg($blank_image, $save_to, 100)) {
                        return true;
                    }

                    break;

                case 'image/png':
                    if (imagepng($blank_image, $save_to, 9)) {
                        return true;
                    }
                    break;
            }
        }
        return false;
    }

}
