<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return Inertia::render('Users', [
            'vouchers' => $vouchers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:vouchers,username',
            'password' => 'required|string',
            'pod_id' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif,terbeli,belum aktif,expired',
            'expired_at' => 'nullable|date',
        ]);

        if (!isset($validated['pod_id'])) {
            $validated['pod_id'] = 1;
        }

        Voucher::create($validated);

        return redirect()->back()->with('message', 'Voucher created successfully.');
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:vouchers,username,' . $voucher->id,
            'password' => 'nullable|string',
            'pod_id' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif,terbeli,belum aktif,expired',
            'expired_at' => 'nullable|date',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $voucher->update($validated);

        return redirect()->back()->with('message', 'Voucher updated successfully.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->back()->with('message', 'Voucher deleted successfully.');
    }
}
