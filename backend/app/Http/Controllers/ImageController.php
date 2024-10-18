<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'imageable_id'      => 'required|integer',
            'imageable_type'    => 'required|string',
            'image'             => 'required|image|max:2048',
        ]);

        $image      = $request->file('image');
        $filename   = time() . '.' . $image->getClientOriginalExtension();
        $url        = $image->storeAs('images', $filename, 'public');

        $imageRecord = Image::create([
            'imageable_id'      => $request->imageable_id,
            'imageable_type'    => $request->imageable_type,
            'filename'          => $filename,
            'url'               => '/storage/' . $url,
        ]);

        return response()->json($imageRecord, 201);
    }

    public function download($id)
    {
        $image = Image::findOrFail($id);

        $filePath = storage_path('app/public/images/' . $image->filename);

        return response()->download($filePath);
    }
}
