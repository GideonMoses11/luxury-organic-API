<?php

namespace App\Repositories\Admin;

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

    public function create()
    {
        request()->validate([
            'name' => 'nullable|string|max:50',
        ]);

        $state = State::create([
           'name' => request()->name
        ]);

        if($state){
            return response()->json([
                    'message' => 'state created successfully!',
                    'status' => 200,
                    'success' => true,
                    'state' => $state
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

    public function edit($id)
    {
        $state = State::find($id);

        request()->validate([
            'name' => 'nullable|string|max:50',
        ]);

        $state->update([
            'name' => !empty(request()->name) ? request()->name : $state->name,
        ]);

        if($state){
            return response()->json([
                    'message' => 'state updated successfully!',
                    'status' => 200,
                    'success' => true,
                    'state' => $state
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
        $state = State::find($id);

        $state->delete();

        if($state){
            return response()->json([
                    'message' => 'state deleted successfully!',
                    'status' => 200,
                    'success' => true,
                    'state' => $state
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
