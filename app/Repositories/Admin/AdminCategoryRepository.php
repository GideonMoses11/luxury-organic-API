<?php

namespace App\Repositories\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCategoryRepository{

    public function listCategory()
    {
        $categories = Category::latest()->get();
        if($categories){
            return response()->json([
                'status' => 200,
                'message' => 'All categories found!',
                'categories' => $categories,
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

    public function createCategory()
    {
        request()->validate([
            'category_name' => 'nullable|string|max:50',
            'icon_image' => 'nullable|max:5480|image|mimes:jpeg,jpg,png,gif,svg',
        ]);

        $baseUrl = config('do-spaces.url');

        $fileUrl = null;

        if(request()->file('icon_image')){
            $cat_image = request()->file('icon_image');
            $file_name = $cat_image->getClientOriginalName();
            $cat_pic = Storage::disk('do_spaces')->putFileAs('category/icons',$cat_image,time().'_'. $file_name, 'public');
            $fileUrl = $baseUrl . '/' . $cat_pic;
        }

        $category = Category::create([
           'category_name' => request()->category_name,
            'slug' => Str::slug(request()->category_name),
           'icon_image' => $fileUrl,

        ]);

        if($category){
            return response()->json([
                    'message' => 'category created successfully!',
                    'status' => 200,
                    'success' => true,
                    'category' => $category
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }

    public function findCategory($id)
    {
        $category = Category::find($id);

        // $category = Category::with('sub_categories')
        // ->with(['activated_products' => function ($query) {
        //     $query->whereHas('ratings')->withCount(['ratings as avg_ratings' => function($query) {
        //         $query->select(DB::raw('avg(scale)'));
        //     }]);
        // }])->find($id);

        if($category){
            return response()->json([
                'status' => 200,
                'message' => 'Category has been found!',
                'category' => $category,
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

    public function showCategory(Category $category, $slug)
    {
        $category = Category::with('sub_categories')
        ->with(['activated_products' => function ($query) {
            $query->whereHas('ratings')->withCount(['ratings as avg_ratings' => function($query) {
                $query->select(DB::raw('avg(scale)'));
            }]);
        }])->where('slug', $slug)->firstOrFail();

        if($category){
            return response()->json([
                'status' => 200,
                'message' => 'Category has been found!',
                'category' => $category,
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

    public function editCategory($id)
    {
        $category = Category::find($id);
        request()->validate([
            'category_name' => 'nullable|string|max:50',
            'icon_image' => 'nullable|max:5480|image|mimes:jpeg,jpg,png,gif,svg',
        ]);

        $baseUrl = config('do-spaces.url');

        $fileUrl = null;

        if(request()->file('icon_image')){
            $cat_image = request()->file('icon_image');
            $file_name = $cat_image->getClientOriginalName();
            $cat_pic = Storage::disk('do_spaces')->putFileAs('category/icons',$cat_image,time().'_'. $file_name, 'public');
            $fileUrl = $baseUrl . '/' . $cat_pic;
        }

        $category->update([
            'category_name' => !empty(request()->category_name) ? request()->category_name : $category->category_name,
            'icon_image' => !empty($fileUrl) ? $fileUrl : $category->icon_image,
            'slug' => !empty(Str::slug(request()->category_name)) ? Str::slug(request()->category_name) : $category->slug,
         ]);

        if($category){
            return response()->json([
                    'message' => 'category updated successfully!',
                    'status' => 200,
                    'success' => true,
                    'category' => $category
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }


    public function destroyCategory($id)
    {
        $category = Category::find($id);
        $category->delete();

        if($category){
            return response()->json([
                    'message' => 'category deleted successfully!',
                    'status' => 200,
                    'success' => true,
                    'category' => $category
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
