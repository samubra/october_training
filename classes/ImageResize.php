<?php

namespace Samubra\Training\Classes;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageIntervention;

class ImageResize
{

    public static function resizeImage($image)
    {
        $resource = 'media';
        $disk = config('cms.storage.'.$resource.'.disk');
        $diskFolder = config('cms.storage.'.$resource.'.folder');
        $originalPath = $diskFolder.$image;
        $quality = 90;
        $thumbFolder = 'thumb';
        if (file_exists(storage_path('/app/media/'.$thumbFolder.'/'.$image)) != 1) {
            // get the image as data
            $originalFile = Storage::disk($disk)->get($originalPath);
            $imageResize = ImageIntervention::make($originalFile);
            //resize
            $imageResize->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
            });
            $thumbDirectory = $diskFolder.'/'.$thumbFolder.'/';
            // create the thumb directory if it does not exist
            if (!Storage::disk($disk)->exists($thumbDirectory)) {
                Storage::disk($disk)->makeDirectory($thumbDirectory);
            }
            $newFileName = str_replace('/', '-', substr($image, 1));
            $lastDot = strrpos($newFileName, '.');
            $extension = substr($newFileName, $lastDot+1);
            $imageStream = $imageResize->stream($extension, $quality);
            Storage::disk($disk)->put($thumbDirectory.$image, $imageStream->__toString());
        }
        return '/storage/app/media/'.$thumbFolder.$image;
    }
}