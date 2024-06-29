<?php

namespace App\Repositories\PickUpLocation;

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

}
