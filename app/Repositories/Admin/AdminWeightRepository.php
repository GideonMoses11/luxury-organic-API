<?php

namespace App\Repositories\Admin;

use App\Models\Weight;

class AdminWeightRepository{

    public function listWeights()
    {
        $weights = Weight::orderBy('name')->get();
        if($weights){
            return response()->json([
                'status' => 200,
                'message' => 'All weights found!',
                'weights' => $weights,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find weights!',
                'success' => false,
            ]);
        }
    }

    public function create()
    {
        request()->validate([
            'name' => 'nullable|string|max:50',
        ]);

        $weight = Weight::create([
           'name' => request()->name
        ]);

        if($weight){
            return response()->json([
                    'message' => 'weight created successfully!',
                    'status' => 200,
                    'success' => true,
                    'weight' => $weight
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
        $weight = Weight::find($id);

        if($weight){
            return response()->json([
                'status' => 200,
                'message' => 'weight has been found!',
                'weight' => $weight,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find weight!',
                'success' => false,
            ]);
        }
    }

    public function edit($id)
    {
        $weight = Weight::find($id);

        request()->validate([
            'name' => 'nullable|string|max:50',
        ]);

        $weight->update([
            'name' => !empty(request()->name) ? request()->name : $weight->name,
        ]);

        if($weight){
            return response()->json([
                    'message' => 'weight updated successfully!',
                    'status' => 200,
                    'success' => true,
                    'weight' => $weight
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
        $weight = Weight::find($id);

        $weight->delete();

        if($weight){
            return response()->json([
                    'message' => 'weight deleted successfully!',
                    'status' => 200,
                    'success' => true,
                    'weight' => $weight
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
