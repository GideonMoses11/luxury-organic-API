<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryRepository{

    public function index()
    {
        $categories = Category::with('products')->latest()->get();
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

    public function show($id)
    {
        $category = Category::with('products')->find($id);

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

}
