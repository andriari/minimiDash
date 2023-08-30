<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

use Intervention\Image\ImageManagerStatic as Image;

trait ImageTrait
{
    public static function upload_image($image, $destinationPath, $resize = false, $width = null, $height = null)
    {
        if ($image != null) {
            $fileName = time() . "_minimi_" . str_replace(" ", "-", $image->getClientOriginalName());
            $size = $image->getSize();
            if ($size >= 1048576) {
                $size_mb = number_format($size / 1048576, 2);
                if ($size_mb >= 20) {
                    return "too_big";
                }
            }

            $quality = 90;

            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            $contentType = mime_content_type($image->getRealPath());
            if (!in_array($contentType, $allowedMimeTypes)) {
                return "not_an_image";
            }

            $image_resize = Image::make($image->getRealPath());
            if ($resize == true) {
                $image_resize->resize($width, $height);
            } else {
                $image_resize->resize(null, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image_resize->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $image = $image_resize->stream();

            $filePath = $destinationPath . '/' . $fileName;
            Storage::disk('s3')->put($filePath, $image->__toString(), 'public');

            $url = 'https://s3.' . config('env.AWS_DEFAULT_REGION') . '.amazonaws.com/' . config('env.AWS_BUCKET');
            $return = $url . '/' . $filePath;
            return $return;
        } else {
            return response()->json(['code' => 400, 'message' => 'image_not_found']);
        }
    }
}
