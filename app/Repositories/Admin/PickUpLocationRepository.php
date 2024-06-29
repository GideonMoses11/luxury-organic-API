<?php

namespace App\Repositories\Admin;

use App\Models\PickUpLocation;

class PickUpLocationRepository{

    public function listLocations()
    {
        $locations = PickUpLocation::orderBy('name')->get();
        if($locations){
            return response()->json([
                'status' => 200,
                'message' => 'All locations found!',
                'locations' => $locations,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find locations!',
                'success' => false,
            ]);
        }
    }

    public function create()
    {
        request()->validate([
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
        ]);

        $location = PickUpLocation::create([
           'name' => request()->name,
           'address' => request()->address,
           'shipping_fee' => request()->shipping_fee,
           'state_id' => request()->state_id
        ]);

        if($location){
            return response()->json([
                    'message' => 'pickup location created successfully!',
                    'status' => 200,
                    'success' => true,
                    'location' => $location
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
        $location = PickUpLocation::find($id);

        if($location){
            return response()->json([
                'status' => 200,
                'message' => 'location has been found!',
                'location' => $location,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find location!',
                'success' => false,
            ]);
        }
    }

    public function edit($id)
    {
        $location = PickUpLocation::find($id);

        request()->validate([
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
        ]);

        $location->update([
            'name' => !empty(request()->name) ? request()->name : $location->name,
            'address' => !empty(request()->address) ? request()->address : $location->address,
            'shipping_fee' => !empty(request()->shipping_fee) ? request()->shipping_fee : $location->shipping_fee,
        ]);

        if($location){
            return response()->json([
                    'message' => 'location updated successfully!',
                    'status' => 200,
                    'success' => true,
                    'location' => $location
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
        $location = PickUpLocation::find($id);

        $location->delete();

        if($location){
            return response()->json([
                    'message' => 'location deleted successfully!',
                    'status' => 200,
                    'success' => true,
                    'location' => $location
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
