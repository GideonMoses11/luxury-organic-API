<?php

namespace App\Repositories\Admin;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AdminOrderRepository{

    public function index(){
        $orders = Order::query()->latest();
        $state = request()->query('state');
        $city = request()->query('city');
        $country = request()->query('country');
        $status = request()->query('status');
        $time = request()->query('time');
        $startDate = request()->query('startDate');
        $endDate = request()->query('endDate');

        $query = $orders
        ->when($state, function($query) use ($state) {
            return $query->whereHas('state', function ($q) use ($state) {
                $q->where('name', $state);
            });
        })
        ->when($city, function($query, $city){
            return $query->where('city', $city);
        })
        ->when($country, function($query, $country){
            return $query->where('country', $country);
        })
        ->when($status, function($query, $status){
            return $query->where('status', $status);
        });

        if($time){
            if($time == 'today'){
                $query = $query->where(DB::raw("date(created_at)"), Carbon::today()->toDateString())
                ->orderBy('created_at', 'desc');
            }
            if($time == '7-days'){
                $query = $query->where('created_at', '>=', today()->subDays(7))
                                    ->where('created_at', '<', today());
                                    // ->orderBy('created_at', 'desc');
            }
            if($time == '1-month'){
                $query = $query->where('created_at', '>=', today()->subDays(30))
                                    ->where('created_at', '<', today());
                                    // ->orderBy('created_at', 'desc');
            }
            if($time == '1-year'){
                $query = $query->where('created_at', '>=', today()->subDays(365))
                                    ->where('created_at', '<', today());
                                    // ->orderBy('created_at', 'desc');
            }
        }
        if($startDate and $endDate){
            $query = $query->whereBetween(DB::raw("date(created_at)"), [date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate))])
                                            ->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(10);

        return $orders;

    }

    public function show($id){
        $order = Order::find($id);
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

    public function editStatus($id){

        $order = Order::find($id);
        request()->validate([
            'status' => 'required|string|max:50',
        ]);

        $order->update([
            'status'=>request()->status
        ]);

        if($order){
        return response()->json([
            'success'=> true,
            'message'=> "Order status has been updated successfully!",
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
