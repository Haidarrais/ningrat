<?php

namespace App\Traits;
use Illuminate\Http\Request;

trait ImageHandlerTrait {
	public function uploadImage($image, $path) {
        if ($image) {
            if (!is_dir(public_path($path))) {
                mkdir(public_path($path), 0777, $rekursif = true);
            }
            $imageName = time(). '.' . $image->extension();;
            $location = public_path($path);
            $image->move($location, $imageName);
            // Image::make($image->image)->save($path.$imageName);
            $image = $imageName;
            return $imageName;
        }
    }

	public function unlinkImage($path, $imageName) {
        $image = str_replace('/', '\\', public_path($path.$imageName));
        if (file_exists($image)) {
            unlink($image);
        }
    }
}
