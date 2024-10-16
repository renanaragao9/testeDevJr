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

        $image       = $request->file('image');
        $filename    = time() . '.' . $image->getClientOriginalExtension();  
        $url         = $image->storeAs('images', $filename, 'public'); // armazena na pasta public
        
        $imageRecord = Image::create([
            'imageable_id'      => $request->imageable_id,
            'imageable_type'    => $request->imageable_type,
            'filename'          => $filename,
            'url'               => '/storage/' . $url, // URL acessível
        ]);

        return response()->json($imageRecord, 201);
    }
}
