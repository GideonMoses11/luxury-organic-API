<?php

namespace App\Repositories\State;

use App\Models\State;

class StateRepository{

    public function listStates()
    {
        $states = State::orderBy('name')->get();
        if($states){
            return response()->json([
                'status' => 200,
                'message' => 'All states found!',
                'states' => $states,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find states!',
                'success' => false,
            ]);
        }
    }

    public function show($id)
    {
        $state = State::find($id);

        if($state){
            return response()->json([
                'status' => 200,
                'message' => 'state has been found!',
                'state' => $state,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to find state!',
                'success' => false,
            ]);
        }
    }

}
