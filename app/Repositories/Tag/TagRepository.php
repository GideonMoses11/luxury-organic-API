<?php

namespace App\Repositories\Tag;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TagRepository{

    public function index()
    {
        $tags = Tag::with('products')->latest()->get();
        if($tags){
            return response()->json([
                'status' => 200,
                'message' => 'All tags found!',
                'tags' => $tags,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find category!',
                'success' => false,
            ]);
        }
    }

    public function show($id)
    {
        $tag = Tag::with('products')->find($id);

        if($tag){
            return response()->json([
                'status' => 200,
                'message' => 'tag has been found!',
                'tag' => $tag,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find tag!',
                'success' => false,
            ]);
        }
    }

}
