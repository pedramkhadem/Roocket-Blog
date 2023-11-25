<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Plank\Mediable\Facades\MediaUploader;

class UploadFileController extends Controller
{
    public function uploadImage(FormRequest $request)
    {
        $request->safe()->all();

        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,webp|max:3048',
        ]);

        $thumbnail = MediaUploader::fromSource($request->file('image'))
            ->useFilename(\Str::ulid())
            ->toDisk('public')
            ->toDirectory('blog/thumbnails')
            ->upload();

            if($thumbnail){
                        return response()->json([
                            'status'=>1,
                            'massage' => 'your media uploaded',
                            'media' => $thumbnail
                        ]);
            }
        }
}
