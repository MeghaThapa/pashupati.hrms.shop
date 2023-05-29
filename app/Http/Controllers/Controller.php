<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use File;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // image upload method
    public function uploadImage($imagePath, $image)
    {
        $imageName = rand().time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path($imagePath), $imageName);
        return $imageName;

    }

    // image delete method
    public function deleteImage($image)
    {
        if(file_exists($image))
        {
            File::delete($image);
        }
        return;

    }

    public function textDispaly()
    {
        return 'Success';
    }
}
