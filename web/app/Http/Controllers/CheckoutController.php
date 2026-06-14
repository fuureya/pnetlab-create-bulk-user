<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $baseUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $response = Http::withBasicAuth($serverKey, '')
            ->post($baseUrl, [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                ],
            ]);

        if ($response->successful()) {
            $snapToken = $response->json('token');
            $transaction->update(['snap_token' => $snapToken]);
            
            return response()->json([
                'snap_token' => $snapToken,
                'transaction' => $transaction
            ]);
        }

        return response()->json(['error' => 'Failed to generate snap token'], 500);
    }
}
