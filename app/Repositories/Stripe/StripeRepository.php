<?php

namespace App\Repositories\Stripe;

use Log;
use Stripe\Stripe;
use App\Models\Payment;
use Stripe\PaymentIntent;

class StripeRepository{

    public function __construct()
    {
        $secret = config('stripe-config.secret');
        // Set your Stripe secret key. Ideally, this should be in your .env file.
        Stripe::setApiKey($secret);
    }

    public function initialize()
    {
        $amount = request()->amount;
        $currency = 'usd'; // Change to your desired currency
        $user = auth()->user();

        // Create a PaymentIntent with the order amount and currency
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount * 100, // Amount in cents
            'currency' => $currency,
            'metadata' => [
                'user_id' => $user->id,
                'order_id' => request()->order_id,
            ],
        ]);

        // Save payment details to the database
        $pay = Payment::create([
            'ref_no' => "REF_" . $paymentIntent->id,
            'tx_ref' => $paymentIntent->id,
            'name' => $user->name,
            'phone' => $user->phone_no,
            'email' => $user->email,
            'amount' => $amount,
            'currency' => $currency,
            'description' => request()->description ?? '',
            'order_id' => request()->order_id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'payment' => $paymentIntent->client_secret,
            'status' => 'success',
            'success' => true,
            'message' => 'Payment initialized successfully!',
        ]);
    }

    public function webhook($request)
    {
        // You should configure Stripe webhook endpoint in your Stripe dashboard
        // and set the secret key in your .env file.

        $endpoint_secret = config('stripe-config.webhook_secret');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handleSuccessfulPayment($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handleFailedPayment($paymentIntent);
                break;
            // Add other event types as needed
            default:
                // Unexpected event type
                return response()->json(['error' => 'Unexpected event type'], 400);
        }

        return response()->json(['status' => 'success'], 200);
    }

    protected function handleSuccessfulPayment($paymentIntent)
    {
        // Find the payment record
        $pay = Payment::where('tx_ref', $paymentIntent->id)->first();

        if ($pay) {
            // Update payment status to 'success'
            $pay->update([
                'status' => 'success'
            ]);

            \Log::info('Payment succeeded for transaction ID: ' . $paymentIntent->id);
        } else {
            \Log::error('Payment record not found for transaction ID: ' . $paymentIntent->id);
        }
    }

    protected function handleFailedPayment($paymentIntent)
    {
        // Find the payment record
        $pay = Payment::where('tx_ref', $paymentIntent->id)->first();

        if ($pay) {
            // Update payment status to 'failed'
            $pay->update([
                'status' => 'rejected'
            ]);

            \Log::info('Payment failed for transaction ID: ' . $paymentIntent->id);
        } else {
            \Log::error('Payment record not found for transaction ID: ' . $paymentIntent->id);
        }
    }



    // public function callback()
    // {
    //     $status = request()->status;

    //     // Log the received status for debugging
    //     \Log::info('Callback received with status: ' . $status);

    //     //if payment is successful
    //     if ($status == 'successful') {

    //         $transactionID = Flutterwave::getTransactionIDFromCallback();

    //         // Log the transaction ID for debugging
    //         Log::info('Transaction ID: ' . $transactionID);

    //         $data = Flutterwave::verifyTransaction($transactionID);

    //         // Log the verification data for debugging
    //         Log::info('Verification data: ' . json_encode($data));

    //         $waveId = $data['data']['tx_ref'];

    //         // Log the Wave ID for debugging
    //         Log::info('Wave ID: ' . $waveId);

    //         $pay = Payment::where('tx_ref', $waveId)->first();

    //         // Log the payment record for debugging
    //         Log::info('Payment record: ' . json_encode($pay));

    //         // Check if payment record is found
    //         if ($pay) {
    //             // Update payment status to 'success'
    //             $updatePay = $pay->update([
    //                 'status' => 'success'
    //             ]);

    //             // Log the update result for debugging
    //             Log::info('Update result: ' . ($updatePay ? 'success' : 'failed'));

    //             // Check if the update was successful
    //             if ($updatePay) {
    //                 Log::info('Redirecting to success page');
    //                 return redirect()->away('https://peppystores23.com/payment-successful');
    //             } else {
    //                 // Redirect to 'payment-failed' if the update fails
    //                 Log::error('Update failed. Redirecting to payment-failed page');
    //                 return redirect()->away('https://peppystores23.com/payment-failed');
    //             }
    //         } else {
    //             // Redirect to 'payment-failed' if payment record is not found
    //             Log::error('Payment record not found. Redirecting to payment-failed page');
    //             return redirect()->away('https://peppystores23.com/payment-failed');
    //         }
    //     } elseif ($status == 'cancelled') {
    //         // Put desired action/code after transaction has been cancelled here
    //         Log::info('Payment cancelled. Redirecting to payment-cancelled page');
    //         return redirect()->away('https://peppystores23.com/payment-cancelled');
    //     } else {
    //         // Put desired action/code after transaction has failed here
    //         Log::error('Payment failed. Redirecting to payment-failed page');
    //         return redirect()->away('https://peppystores23.com/payment-failed');
    //     }
    // }




//     public function webhook($request)
//   {
//     //This verifies the webhook is sent from Flutterwave
//     $verified = Flutterwave::verifyWebhook();

//     // if it is a charge event, verify and confirm it is a successful transaction
//     if ($verified && $request->event == 'charge.completed' && $request->data->status == 'successful') {
//         $verificationData = Flutterwave::verifyPayment($request->data['id']);
//         if ($verificationData['status'] === 'success') {
//         // process for successful charge
//         return response()->json([
//             'verificationData' => $verificationData,
//             'status' => 'success',
//             'success' => true
//         ]);

//         }

//     }

//     // if it is a transfer event, verify and confirm it is a successful transfer
//     if ($verified && $request->event == 'transfer.completed') {

//         $transfer = Flutterwave::transfers()->fetch($request->data['id']);

//         if($transfer['data']['status'] === 'SUCCESSFUL') {
//             // update transfer status to successful in your db
//         } else if ($transfer['data']['status'] === 'FAILED') {
//             // update transfer status to failed in your db
//             // revert customer balance back
//         } else if ($transfer['data']['status'] === 'PENDING') {
//             // update transfer status to pending in your db
//         }

//     }
//   }

}
