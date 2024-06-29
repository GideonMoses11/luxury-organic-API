<?php

namespace App\Repositories\Flutterwave;

use Log;
use App\Models\Payment;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterwaveRepository{

    public function initialize()
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => request()->amount,
            'email' => !empty(request()->email) ? request()->email : auth()->user()->email,
            'tx_ref' => $reference,
            'currency' => "NGN",
            'redirect_url' => route('callback'),
            'customer' => [
                'email' => !empty(request()->email) ? request()->email : auth()->user()->email,
                "phone_number" => !empty(request()->phone) ? request()->phone : auth()->user()->phone_no,
                "name" => !empty(request()->name) ? request()->name : auth()->user()->fullName()
            ],

            "customizations" => [
                "title" => request()->title,
                "description" => request()->description
            ]
        ];

        $payment = Flutterwave::initializePayment($data);

        $pay = Payment::create([
            'ref_no' => "REF_".$reference,
            'tx_ref' => $reference,
            'name' => !empty(request()->name) ? request()->name : auth()->user()->fullName(),
            'phone' => !empty(request()->phone) ? request()->phone : auth()->user()->phone_no,
            'email' => !empty(request()->email) ? request()->email : auth()->user()->email,
            'amount' => request()->amount,
            'currency' => 'NGN',
            'description' => !empty(request()->description) ? request()->description : '',
            'order_id' => request()->order_id,
            'user_id' => auth()->user()->id,
        ]);


        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return response()->json([
                'payment' => $payment,
                'status' => 'failed',
                'success' => false,
                'message' => 'payment was not successful!'
            ]);
        }

        if (!$payment) {
            // notify something went wrong
            return response()->json([
                'payment' => $payment,
                'status' => 'failed',
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }

        return response()->json([
                'payment' => $payment['data']['link'],
                'status' => 'success',
                'success' => true,
                'message' => 'Payment initialized successfully!'
            ]);
    }

// public function callback()
// {
//     $status = request()->status;

//     //if payment is successful
//     if ($status == 'successful') {

//         $transactionID = Flutterwave::getTransactionIDFromCallback();

//         $data = Flutterwave::verifyTransaction($transactionID);

//         $waveId = $data['data']['tx_ref'];

//         $pay = Payment::where('tx_ref', $waveId)->first();

//         // Check if payment record is found
//         if ($pay) {
//             // Update payment status to 'success'
//             $updatePay = $pay->update([
//                 'status' => 'success'
//             ]);

//             // Check if the update was successful
//             if ($updatePay) {
//                 return redirect()->away('https://peppi.vercel.app/payment-successful');
//             } else {
//                 // Redirect to 'payment-failed' if the update fails
//                 return redirect()->away('https://peppi.vercel.app/payment-failed');
//             }
//         } else {
//             // Redirect to 'payment-failed' if payment record is not found
//             return redirect()->away('https://peppi.vercel.app/payment-failed');
//         }
//     } elseif ($status == 'cancelled') {
//         // Put desired action/code after transaction has been cancelled here
//         return redirect()->away('https://peppi.vercel.app/payment-cancelled');
//     } else {
//         // Put desired action/code after transaction has failed here
//         return redirect()->away('https://peppi.vercel.app/payment-failed');
//     }
// }

public function callback()
{
    $status = request()->status;

    // Log the received status for debugging
    \Log::info('Callback received with status: ' . $status);

    //if payment is successful
    if ($status == 'successful') {

        $transactionID = Flutterwave::getTransactionIDFromCallback();

        // Log the transaction ID for debugging
        Log::info('Transaction ID: ' . $transactionID);

        $data = Flutterwave::verifyTransaction($transactionID);

        // Log the verification data for debugging
        Log::info('Verification data: ' . json_encode($data));

        $waveId = $data['data']['tx_ref'];

        // Log the Wave ID for debugging
        Log::info('Wave ID: ' . $waveId);

        $pay = Payment::where('tx_ref', $waveId)->first();

        // Log the payment record for debugging
        Log::info('Payment record: ' . json_encode($pay));

        // Check if payment record is found
        if ($pay) {
            // Update payment status to 'success'
            $updatePay = $pay->update([
                'status' => 'success'
            ]);

            // Log the update result for debugging
            Log::info('Update result: ' . ($updatePay ? 'success' : 'failed'));

            // Check if the update was successful
            if ($updatePay) {
                Log::info('Redirecting to success page');
                return redirect()->away('https://peppystores23.com/payment-successful');
            } else {
                // Redirect to 'payment-failed' if the update fails
                Log::error('Update failed. Redirecting to payment-failed page');
                return redirect()->away('https://peppystores23.com/payment-failed');
            }
        } else {
            // Redirect to 'payment-failed' if payment record is not found
            Log::error('Payment record not found. Redirecting to payment-failed page');
            return redirect()->away('https://peppystores23.com/payment-failed');
        }
    } elseif ($status == 'cancelled') {
        // Put desired action/code after transaction has been cancelled here
        Log::info('Payment cancelled. Redirecting to payment-cancelled page');
        return redirect()->away('https://peppystores23.com/payment-cancelled');
    } else {
        // Put desired action/code after transaction has failed here
        Log::error('Payment failed. Redirecting to payment-failed page');
        return redirect()->away('https://peppystores23.com/payment-failed');
    }
}




    public function webhook($request)
  {
    //This verifies the webhook is sent from Flutterwave
    $verified = Flutterwave::verifyWebhook();

    // if it is a charge event, verify and confirm it is a successful transaction
    if ($verified && $request->event == 'charge.completed' && $request->data->status == 'successful') {
        $verificationData = Flutterwave::verifyPayment($request->data['id']);
        if ($verificationData['status'] === 'success') {
        // process for successful charge
        return response()->json([
            'verificationData' => $verificationData,
            'status' => 'success',
            'success' => true
        ]);

        }

    }

    // if it is a transfer event, verify and confirm it is a successful transfer
    if ($verified && $request->event == 'transfer.completed') {

        $transfer = Flutterwave::transfers()->fetch($request->data['id']);

        if($transfer['data']['status'] === 'SUCCESSFUL') {
            // update transfer status to successful in your db
        } else if ($transfer['data']['status'] === 'FAILED') {
            // update transfer status to failed in your db
            // revert customer balance back
        } else if ($transfer['data']['status'] === 'PENDING') {
            // update transfer status to pending in your db
        }

    }
  }

}
