<?php

namespace App\Repositories\Admin;

use Carbon\Carbon;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class AdminPaymentRepository{

    public function index(){
        $payments = Payment::query()->with('user', 'order');
        $name = request()->query('name');
        $ref_no = request()->query('ref_no');
        $tx_ref = request()->query('tx_ref');
        $phone = request()->query('phone');
        $email = request()->query('email');
        $status = request()->query('status');
        $channel = request()->query('channel');

        $query = $payments
        ->when($name, function ($query, $name){
            return $query->where('name', 'LIKE', "%{$name}%");
        })
        ->when($status, function($query, $status){
            return $query->where('status', $status);
        })
        ->when($ref_no, function($query, $ref_no){
            return $query->where('ref_no', $ref_no);
        })
        ->when($tx_ref, function($query, $tx_ref){
            return $query->where('tx_ref', $tx_ref);
        })
        ->when($phone, function($query, $phone){
            return $query->where('phone', $phone);
        })
        ->when($email, function($query, $email){
            return $query->where('email', $email);
        })
        ->when($channel, function($query, $channel){
            return $query->where('channel', $channel);
        })
        ->latest()->paginate(10);

        return $query;
    }

    public function show($id)
    {
        $payment = Payment::with('user', 'order')->find($id);

        if($payment){
            return response()->json([
                    'message' => 'payment found successfully!',
                    'status' => 200,
                    'success' => true,
                    'payment' => $payment
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
