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
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($expectedSignature !== $signatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('order_id', $orderId)->first();
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transactionStatus = $request->transaction_status;

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
