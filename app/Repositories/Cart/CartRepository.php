<?php

namespace App\Repositories\Cart;

use App\Models\Cart;

class CartRepository{

    public function index()
    {
        $cart_list = null;
        
        if(auth()->check()){
            $cart_list = Cart::where('user_id', auth()->user()->id)->latest()->get();
        }

        if(!auth()->check()){
            $cart_list = session()->get('cart', []);
            return response()->json([
                'message' => 'Session cart found!',
                'status' => 200,
                'success' => true,
                'cart_list' => $cart_list
            ]);
        }
        if($cart_list){
            return response()->json([
                    'message' => 'User cart list found!',
                    'status' => 200,
                    'success' => true,
                    'cart_list' => $cart_list
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }

    // public function create()
    // {
    //     request()->validate([
    //         'quantity' => 'integer|nullable',
    //     ]);

    //     $cart_item = Cart::create([
    //         'quantity' => request()->quantity,
    //         'product_id' => request()->product_id,
    //         'user_id' => auth()->user()->id,
    //     ]);

    //     if($cart_item){
    //         return response()->json([
    //                 'message' => 'cart item added successfully!',
    //                 'status' => 200,
    //                 'success' => true,
    //                 'cart_item' => $cart_item
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 401,
    //                 'message' => 'Something went wrong!',
    //                 'success' => false,
    //             ]);
    //     }
    // }

    public function create()
    {
        request()->validate([
            'quantity' => 'integer|nullable',
        ]);

        $cart_item = null;
        $cart = null;

        if(!auth()->check()){
            $cart = session()->get('cart', []);
            $cart[] = [
                'quantity' => request()->quantity,
                'product_id' => request()->product_id,
            ];
            session()->put('cart', $cart);
        }

        // Check if user is authenticated
        if (auth()->check()) {
            // User is authenticated, add item to the database
            $cart_item = Cart::create([
                'quantity' => request()->quantity,
                'product_id' => request()->product_id,
                'user_id' => auth()->user()->id,
            ]);
        }
        // else {
        //     // User is not authenticated, store cart data in session
        //     $cart = session()->get('cart', []);
        //     $cart[] = [
        //         'quantity' => request()->quantity,
        //         'product_id' => request()->product_id,
        //     ];
        //     session()->put('cart', $cart);
        // }

        if($cart_item || $cart){
            return response()->json([
                'message' => 'Cart item added successfully!',
                'status' => 200,
                'success' => true,
                'cart_item' => $cart_item
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
            ]);
        }
    }

    public function edit($id)
    {
        $cart_item = auth()->user()->cart()->find($id);

        request()->validate([
            'quantity' => 'integer|nullable',
        ]);

        $cart_item->update([
            'quantity' => request()->quantity,
        ]);

        if($cart_item){
            return response()->json([
                    'message' => 'cart item updated successfully!',
                    'status' => 200,
                    'success' => true,
                    'cart_item' => $cart_item
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
        $user_cart = auth()->user()->cart()->find($id);
        $user_cart->delete();

        if($user_cart){
            return response()->json([
                    'message' => 'cart item removed successfully!',
                    'status' => 200,
                    'success' => true,
                    'user_cart' => $user_cart
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
