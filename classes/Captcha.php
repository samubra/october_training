<?php

namespace Samubra\Training\Classes;

use Samubra\Training\Models\Training;

class Captcha
{
    public $img_width      =   150;
    public $img_height     =   40;
    public $font_path      =   'fonts'; //path to folder file ext
    public $fonts          =   [];
    public $font_size      =   26;
    public $char_set       =   "ABCDEFGHJKLMNPQRSTUVWXYZ2345689";
    public $char_length    =   5;
    public $char_color     =   "#880000,#008800,#000088,#888800,#880088,#008888,#000000";
    public $char_colors    =   [];
    public $line_count     =   10;
    public $line_color     =   "#DD6666,#66DD66,#6666DD,#DDDD66,#DD66DD,#66DDDD,#666666";
    public $line_colors    =   [];

    public $bg_color       =   '#FFFFFF';

    // display image
    public function getAndShowImage($override = [])
    {
        // Override lại thong số config
        if (is_array($override)) {
            foreach ($override as $key => $value) {
                if (isset($this->$key)) {
                    $this->$key = $value;
                }
            }
        }

        // Create array list color
        $this->line_colors = preg_split("/,\s*?/", $this->line_color);
        $this->char_colors = preg_split("/,\s*?/", $this->char_color);

        // get list of fonts
        $this->fonts = $this->collectFile($this->font_path, "ttf");

        // initialize image
        $img = imagecreatetruecolor($this->img_width, $this->img_height);
        $width = $this->img_width - 1;
        $height = $this->img_height - 1;
        imagefilledrectangle($img, 0, 0, $width, $height, $this->gdColor($this->bg_color));


        // draw some random line
//        for ($i = 0; $i < $this->line_count; $i++) {
//            imageline(
//                $img,
//                rand(0, $this->img_width  - 1),
//                rand(0, $this->img_height - 1),
//                rand(0, $this->img_width  - 1),
//                rand(0, $this->img_height - 1),
//                $this->gdColor($this->line_colors[rand(0, count($this->line_colors) - 1)])
//            );
//        }

        // draw code to image
        $code = "";
        $y = ($this->img_height / 2) + ( $this->font_size / 2);

        for ($i = 0; $i<$this->char_length; $i++) {
            $color = $this->gdColor($this->char_colors[rand(0, count($this->char_colors) - 1)]);
            $angle = rand(-30, 30);
            $char = substr($this->char_set, rand(0, strlen($this->char_set) - 1), 1);
            $sel_font = $this->fonts[rand(0, count($this->fonts) - 1)];
            $font = plugins_path('/ideas/cart/classes/'.$this->font_path) . "/" . $sel_font;
            $x = (intval(( $this->img_width / $this->char_length) * $i) + ( $this->font_size / 2));
            $code .= $char;
            imagettftext($img, $this->font_size, $angle, $x, $y, $color, $font, $char);
        }

        //display image
//        header('content-type: image/jpg');
//        ImageJPeg($img);
        header('Content-Type: image/jpeg');
        $randomName = Training::generateRandomString(6);
        //create folder 'captcha' if not exists
        if (!file_exists(storage_path('/app/media/captcha'))) {
            mkdir(storage_path('/app/media/captcha'), 0777, true);
        }

        $fileImagePath = '/app/media/captcha/'.$randomName.'.jpeg';
        //use function ImageJPeg() to save to storage
        ImageJPeg($img, storage_path($fileImagePath));

        $rs = [
            'code' => $code,
            'image' => $randomName.'.jpeg'
        ];
        return $rs;
    }

    //change color
    public function gdColor($html_color)
    {
        return preg_match('/^#?([\dA-F]{6})$/i', $html_color, $rgb)
            ? hexdec($rgb[1]) : false;
    }

    //Get list of files by ext
    public function collectFile($dir, $ext)
    {
        if (false !== ($dir = opendir(plugins_path('/samubra/training/classes/'.$dir)))) {
            $files = array();
            while (false !== ($file = readdir($dir))) {
                if (preg_match("/\\.$ext\$/i", $file)) {
                    $files[] = $file;
                }
            }
            return $files;
        }
        return false;
    }
}