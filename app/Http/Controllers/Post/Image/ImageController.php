<?php

namespace App\Http\Controllers\Post\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\Image\StoreRequest;
use App\Models\Image;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {

    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $image = $data['image'];

        $name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
        $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);

        return response()->json(['url' => url('/storage/' . $filePath)]);
    }
}

