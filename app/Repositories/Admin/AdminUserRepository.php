<?php

namespace App\Repositories\Admin;

use App\Models\User;

class AdminUserRepository{

    public function index(){
        $users = User::query();
        $role = request()->query('role');
        $username = request()->query('username');
        // $status = request()->query('status');
        // $type = request()->query('type');
        // $first_name = request()->query('first_name');
        // $last_name = request()->query('last_name');
        $email = request()->query('email');

        $query = $users
        // ->where('first_name', 'LIKE', "%{$first_name}%")
        // ->where('last_name', 'LIKE', "%{$last_name}%")
        ->where('username', 'LIKE', "%{$username}%")
        ->where('email', 'LIKE', "%{$email}%")
        ->when($role, function($query, $role){
            return $query->where('role', $role);
        })
        // ->when($status, function($query, $status){
        //     return $query->where('status', $status);
        // })->when($type, function($query, $type){
        //     return $query->where('type', $type);
        // })
        ->latest()->paginate(10);

        return $query;
    }

    public function show($id)
    {
        $user = User::find($id);

        if($user){
            return response()->json([
                    'message' => 'user found successfully!',
                    'status' => 200,
                    'success' => true,
                    'user' => $user
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
