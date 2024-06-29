<?php

namespace App\Repositories\Admin;

use App\Models\WeightPricing;

class WeightPricingRepository{

    public function index()
    {
        $weightPricings = WeightPricing::latest()->paginate(12);
        
        if($weightPricings){
            return response()->json([
                'status' => 200,
                'message' => 'All weightPricings found!',
                'weightPricings' => $weightPricings,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find weight pricings!',
                'success' => false,
            ]);
        }
    }

    public function create()
    {
        request()->validate([
            'weight_id' => 'nullable|string',
            'price' => 'nullable',
            'pickup_location_id' => 'nullable|string',
            'state_id' => 'nullable|string',
        ]);

        $weightPricing = WeightPricing::create([
           'weight_id' => request()->weight_id,
           'price' => request()->price,
           'pickup_location_id' => request()->pickup_location_id,
           'state_id' => request()->state_id
        ]);

        if($weightPricing){
            return response()->json([
                    'message' => 'weight pricing created successfully!',
                    'status' => 200,
                    'success' => true,
                    'weightPricing' => $weightPricing
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }

    public function show($id)
    {
        $weightPricing = WeightPricing::find($id);

        if($weightPricing){
            return response()->json([
                'status' => 200,
                'message' => 'weightPricing has been found!',
                'weightPricing' => $weightPricing,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find weightPricing!',
                'success' => false,
            ]);
        }
    }

    public function edit($id)
    {
        $weightPricing = WeightPricing::find($id);

        request()->validate([
            'weight_id' => 'nullable|string',
            'price' => 'nullable',
            'pickup_location_id' => 'nullable|string',
            'state_id' => 'nullable|string',
        ]);

        $weightPricing->update([
            'weight_id' => !empty(request()->weight_id) ? request()->weight_id : $weightPricing->weight_id,
            'price' => !empty(request()->price) ? request()->price : $weightPricing->price,
            'pickup_location_id' => !empty(request()->pickup_location_id) ? request()->pickup_location_id : $weightPricing->pickup_location_id,
            'state_id' => !empty(request()->state_id) ? request()->state_id : $weightPricing->state_id,
        ]);

        if($weightPricing){
            return response()->json([
                    'message' => 'weightPricing updated successfully!',
                    'status' => 200,
                    'success' => true,
                    'weightPricing' => $weightPricing
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
       $weightPricing = WeightPricing::find($id);

       $weightPricing->delete();

        if($weightPricing){
            return response()->json([
                    'message' => 'weightPricing deleted successfully!',
                    'status' => 200,
                    'success' => true,
                    'weightPricing' =>$weightPricing
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
