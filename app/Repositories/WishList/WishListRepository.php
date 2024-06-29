<?php

namespace App\Repositories\WishList;

use App\Models\WishList;

class WishListRepository{

    public function index()
    {
        $wish_list = WishList::where('user_id', auth()->user()->id)->latest()->get();
        if($wish_list){
            return response()->json([
                    'message' => 'User wish list found!',
                    'status' => 200,
                    'success' => true,
                    'wish_list' => $wish_list
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }

    public function create()
    {
        $duplicate = WishList::where('user_id',auth()->user()->id)
        ->where('product_id',request()->product_id)
        ->first();

        if(isset($duplicate->user_id) and isset(request()->product_id)){
            return response()->json([
                'status' => 402,
                'message' => 'This item is already in your wish list!',
                'success' => false,
            ]);
        }

        $wish_list = WishList::create([
            'product_id' => request()->product_id,
            'user_id' => auth()->user()->id
        ]);

        if($wish_list){
            return response()->json([
                    'message' => 'item has been added to wish list successfully!',
                    'status' => 200,
                    'success' => true,
                    'wish_list' => $wish_list
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }

    public function destroy($id)
    {
        $wish_list = WishList::find($id);

        if($wish_list->user_id != auth()->user()->id){
            return response()->json([
                'status' => 401,
                'message' => 'Bad Request',
                'success' => false,
            ]);
        }

        $wish_list->delete();

        if($wish_list){
            return response()->json([
                    'message' => 'wish list item removed successfully!',
                    'status' => 200,
                    'success' => true,
                    'wish_list' => $wish_list
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
