<?php

namespace App\Repositories\Product;

use App\Models\Image;
use App\Models\PickUpLocation;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductRepository{

    public function index()
    {
        $products = Product::with('reviews', 'ratings')->inRandomOrder()->paginate(12);
        if($products){
            return response()->json([
                'status' => 200,
                'message' => 'All products found!',
                'products' => $products,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find products!',
                'success' => false,
            ]);
        }
    }

    public function find($id)
{
    $product = Product::with('reviews', 'ratings')->find($id);

    if($product){
        return response()->json([
            'status' => 200,
            'message' => 'product has been found!',
            'product' => $product,
            'success' => true,
        ]);
    } else {
        return response()->json([
            'status' => 401,
            'message' => 'Failed to find product!',
            'success' => false,
        ]);
    }
}

public function productWeightFees($id, $pickupLocationId){

    $product = Product::find($id);

    $weightFees = $product->calculateWeightFee($pickupLocationId);

    if($weightFees){
        return response()->json([
            'status' => 200,
            'message' => 'weightFees has been found!',
            'weightFees' => $weightFees,
            'success' => true,
        ]);
    } else {
        return response()->json([
            'status' => 401,
            'message' => 'Failed to find weightFees!',
            'success' => false,
        ]);
    }
}




}
