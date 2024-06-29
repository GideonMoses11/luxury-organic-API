<?php

namespace App\Repositories\Admin;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminTagRepository{

    public function listTag()
    {
        $tags = Tag::latest()->get();
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
                'message' => 'Failed to find tag!',
                'success' => false,
            ]);
        }
    }

    public function createTag()
    {
        request()->validate([
            'name' => 'string|max:50',
        ]);

        $tag = Tag::create([
            'name' => request()->name,
            'slug' => Str::slug(request()->name),
        ]);

        if($tag){
            return response()->json([
                    'message' => 'tag created successfully!',
                    'status' => 200,
                    'success' => true,
                    'tag' => $tag
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }

    public function findTag($id)
    {
        $tag = Tag::find($id);

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

    // public function showCategory(Category $category, $slug)
    // {
    //     $category = Category::with('sub_categories')
    //     ->with(['activated_products' => function ($query) {
    //         $query->whereHas('ratings')->withCount(['ratings as avg_ratings' => function($query) {
    //             $query->select(DB::raw('avg(scale)'));
    //         }]);
    //     }])->where('slug', $slug)->firstOrFail();

    //     if($category){
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Category has been found!',
    //             'category' => $category,
    //             'success' => true,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 401,
    //             'message' => 'Failed to find category!',
    //             'success' => false,
    //         ]);
    //     }
    // }

    public function editTag($id)
    {
        $tag = Tag::find($id);
        request()->validate([
            'category_name' => 'nullable|string|max:50',
            'icon_image' => 'nullable|max:5480|image|mimes:jpeg,jpg,png,gif,svg',
        ]);

        $tag->update([
            'name' => !empty(request()->name) ? request()->name : $tag->name,
            'slug' => !empty(Str::slug(request()->name)) ? Str::slug(request()->name) : $tag->slug,
         ]);

        if($tag){
            return response()->json([
                    'message' => 'tag updated successfully!',
                    'status' => 200,
                    'success' => true,
                    'tag' => $tag
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }


    public function destroyTag($id)
    {
        $tag = Tag::find($id);
        $tag->delete();

        if($tag){
            return response()->json([
                    'message' => 'tag deleted successfully!',
                    'status' => 200,
                    'success' => true,
                    'tag' => $tag
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
        }
    }
}
