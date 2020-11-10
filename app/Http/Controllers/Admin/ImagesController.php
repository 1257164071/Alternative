<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    //
    public function store(ImageUploadHandler $uploadHandler, Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,bmp,png,gif',
        ]);

        $result = $uploadHandler->save($request->image, 'image', 'hj_1');

        return response()->json([
            'path' => $result['url']
        ]);
    }
}
