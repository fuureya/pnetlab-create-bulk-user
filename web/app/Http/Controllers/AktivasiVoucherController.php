<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class AktivasiVoucherController extends Controller
{
    public function index()
    {
        return Inertia::render('AktivasiVoucher');
    }

    public function activate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $voucher = Voucher::where('username', $request->username)
            ->where('password', $request->password)
            ->first();

        if (!$voucher) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ]);
        }

        // Cek jika voucher sudah dimiliki oleh akun lain
        if ($voucher->user_id !== null && $voucher->user_id !== auth()->id()) {
            return back()->withErrors([
                'username' => 'Voucher ini bukan milik Anda atau sudah diklaim oleh akun lain.',
            ]);
        }

        if ($voucher->status === 'aktif') {
            return back()->withErrors([
                'username' => 'Voucher sudah aktif.',
            ]);
        }

        if ($voucher->status === 'nonaktif') {
            return back()->withErrors([
                'username' => 'Voucher ini dinonaktifkan oleh administrator.',
            ]);
        }

        if ($voucher->status === 'expired') {
            return back()->withErrors([
                'username' => 'Voucher ini sudah kedaluwarsa.',
            ]);
        }

        // Send API request ke server lab PNETLab
        try {
            $apiUrl = env('PNETLAB_API_URL', 'http://domain.com/bulkuser/api/v2/users');
            $apiKey = env('PNETLAB_API_KEY', 'YOUR_API_KEY_HERE');

            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post($apiUrl, [
                'username' => $voucher->username,
                'password' => $request->password, // Use the plain password they inputted
                'name' => $voucher->username, // Using username as the name by default
                'expired_days' => $voucher->duration_days
            ]);

            if (!$response->successful()) {
                return back()->withErrors([
                    'username' => 'Gagal membuat user di server lab: ' . $response->body()
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors([
                'username' => 'Koneksi ke server lab gagal: ' . $e->getMessage()
            ]);
        }

        // Jika API sukses, kaitkan voucher ke user dan aktifkan
        $voucher->user_id = auth()->id();
        $voucher->status = 'aktif';
        $voucher->activated_at = Carbon::now();
        if ($voucher->duration_days) {
            $voucher->expired_at = Carbon::now()->addDays($voucher->duration_days);
        }
        $voucher->save();

        return back()->with('success', 'Voucher berhasil diaktivasi!');
    }
}
