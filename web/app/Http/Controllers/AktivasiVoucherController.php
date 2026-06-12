<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Voucher;
use Carbon\Carbon;

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

        if ($voucher->status === 'active') {
            return back()->withErrors([
                'username' => 'Voucher sudah aktif.',
            ]);
        }

        $voucher->status = 'active';
        $voucher->activated_at = Carbon::now();
        if ($voucher->duration_days) {
            $voucher->expired_at = Carbon::now()->addDays($voucher->duration_days);
        }
        $voucher->save();

        return back()->with('success', 'Voucher berhasil diaktivasi!');
    }
}
