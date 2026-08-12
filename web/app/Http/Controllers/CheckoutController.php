<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Clean price string to integer (e.g. "Rp 150.000" -> 150000)
        $priceStr = preg_replace('/[^0-9]/', '', $product->price);
        $grossAmount = (int) $priceStr;

        $orderId = 'TRX-' . time() . '-' . $request->user()->id;

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'order_id' => $orderId,
            'gross_amount' => $grossAmount,
            'status' => 'pending'
        ]);

        // Request Snap Token
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'callbacks' => [
                'finish' => env('MIDTRANS_FINISH_REDIRECT_URL', 'http://localhost:8000/riwayat-transaksi'),
                'unfinish' => env('MIDTRANS_UNFINISH_REDIRECT_URL', 'http://localhost:8000/riwayat-transaksi'),
                'error' => env('MIDTRANS_ERROR_REDIRECT_URL', 'http://localhost:8000/riwayat-transaksi'),
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);
            
            return response()->json([
                'snap_token' => $snapToken,
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate snap token: ' . $e->getMessage()], 500);
        }
    }
}
