<?php

namespace App\Repositories\Rating;

use App\Models\Rating;

class RatingRepository{

    public function create(){

        request()->validate([
            'scale' => 'numeric|required',
        ]);

        $user_id = auth()->user() ? auth()->user()->id : null;

        $rating = Rating::create([
            'scale' => request()->scale,
            'user_id' => $user_id,
            'product_id' => request()->product_id,
        ]);

        if($rating){
            return response()->json([
                    'message' => 'Rating added successfully!',
                    'status' => 200,
                    'success' => true,
                    'rating' => $rating,
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
