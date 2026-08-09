<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MidtransWebhookController extends Controller
{
    public function callback(Request $request)
    {
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
            }
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->update(['status' => 'failed']);
        } elseif ($transactionStatus == 'pending') {
            $transaction->update(['status' => 'pending']);
        }

        return response()->json(['message' => 'OK']);
    }
}
