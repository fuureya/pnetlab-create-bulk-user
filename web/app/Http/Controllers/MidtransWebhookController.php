<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionNotification;

class MidtransWebhookController extends Controller
{
    public function callback(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Midtrans Webhook Received: ', $request->all());
        
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            $notification = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification: ' . $e->getMessage()], 403);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;

        $transaction = Transaction::where('order_id', $orderId)->first();
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($transaction->status !== 'success') {
                $transaction->update(['status' => 'success']);

                // Generate Voucher for the user
                Voucher::create([
                    'user_id' => $transaction->user_id,
                    'transaction_id' => $transaction->id,
                    'username' => strtolower(Str::random(8)),
                    'password' => Str::random(8),
                    'duration_days' => $transaction->product->duration_days,
                    'status' => 'belum aktif'
                ]);

                // Reload the transaction to get the newly created vouchers
                $transaction->load('vouchers');
                
                // Send success email
                Mail::to($transaction->user->email)->send(new TransactionNotification($transaction));
            }
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->update(['status' => 'failed']);
            Mail::to($transaction->user->email)->send(new TransactionNotification($transaction));
        } elseif ($transactionStatus == 'pending') {
            $transaction->update(['status' => 'pending']);
            // We usually don't send an email every time we get a pending webhook,
            // as they get a pending email when they click checkout or first create it.
        }

        return response()->json(['message' => 'OK']);
    }
}
