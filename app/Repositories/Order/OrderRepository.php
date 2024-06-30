<?php

namespace App\Repositories\Order;

use App\Models\Order;

class OrderRepository{

    public function create(){

        $user_carts = auth()->user()->cart;

        $order = Order::create([
            'total_quantity' => request()->total_quantity,
            'total_amount' => request()->total_amount,
            'delivery_address' => request()->delivery_address,
            'city' => request()->city,
            'state' => request()->state,
            'country' => request()->country,
            'user_id' => auth()->user()->id
        ]);

        foreach($user_carts as $user_cart){
                $order->products()->attach($user_cart,[
                    'amount' => request()->amount,
                    'quantity' => $user_cart->quantity,
                    'order_id' => $order->id,
                    'product_id' => $user_cart->product_id
                ]);
        }

        $user_orders = Order::where('user_id', auth()->user()->id)->paginate();

        if($order){
            return response()->json([
                    'message' => 'order created successfully!',
                    'status' => 200,
                    'success' => true,
                    'order' => $order,
                    'user_orders' => $user_orders,
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
        }

    }

    public function userOrders(){
        $orders = Order::where('user_id', auth()->user()->id)->paginate();
        if($orders){
            return response()->json([
                    'message' => 'orders found successfully!',
                    'status' => 200,
                    'success' => true,
                    'orders' => $orders,
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
        }
    }

    public function show($id){
        $order = Order::find($id);
        if(auth()->user()->id != $order->user_id){
            return response()->json([
                'status' => 402,
                'message' => 'Bad Request',
                'success' => false,

            ]);
        }
        if($order){
            return response()->json([
                'success'=> true,
                'message'=> "Order has been found successfully!",
                'order'=> $order,
             ],200);
            }else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
            }
    }



}
