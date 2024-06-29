<?php

namespace App\Repositories\Review;

use App\Models\Review;

class ReviewRepository{

    public function create(){

        request()->validate([
            'comment' => 'string|required',
        ]);

        if(!auth()->check()){
            $review = Review::create([
                'name' => request()->name,
                'comment' => request()->comment,
                'product_id' => request()->product_id,
            ]);

            return response()->json([
                'message' => 'Review created successfully!',
                'status' => 200,
                'success' => true,
                'review' => $review,
            ]);
        }

        if(auth()->check()){
            $review = Review::create([
                'comment' => request()->comment,
                'user_id' => auth()->user()->id,
                'product_id' => request()->product_id,
            ]);

            if($review){
                return response()->json([
                        'message' => 'Review created successfully!',
                        'status' => 200,
                        'success' => true,
                        'review' => $review,
                    ]);
                } else {
                    return response()->json([
                        'status' => 401,
                        'message' => 'Something went wrong!',
                        'success' => false,
                    ]);
            }
        }

        // $review = Review::create([
        //     'comment' => request()->comment,
        //     'user_id' => auth()->user()->id,
        //     'product_id' => request()->product_id,
        // ]);

        // if($review){
        //     return response()->json([
        //             'message' => 'Review created successfully!',
        //             'status' => 200,
        //             'success' => true,
        //             'review' => $review,
        //         ]);
        //     } else {
        //         return response()->json([
        //             'status' => 401,
        //             'message' => 'Something went wrong!',
        //             'success' => false,
        //         ]);
        // }
    }

    public function edit($id){

        $review = Review::where('id',$id)->first();

        if($review->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Bad Request!',
                'status' => 400,
            ]);
        }

        request()->validate([
            'comment' => 'string|required',
        ]);

        $review->update([
            'comment' => request()->comment ? request()->comment : $review->comment,
        ]);

        if($review){
            return response()->json([
                    'message' => 'Review updated successfully!',
                    'status' => 200,
                    'success' => true,
                    'review' => $review,
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
        }

    }

    public function destroy($id){

        $review = Review::find($id);

        if($review->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Bad Request!',
                'status' => 400,
            ]);
        }

        $review->delete();

        if($review){
            return response()->json([
                    'message' => 'review deleted successfully!',
                    'status' => 200,
                    'success' => true,
                    'review' => $review
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
