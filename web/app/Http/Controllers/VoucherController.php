<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(15);
        return Inertia::render('Users', [
            'vouchers' => $vouchers
        ]);
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:100',
            'duration_days' => 'required|integer',
        ]);

        $count = $validated['count'];
        $durationDays = $validated['duration_days'];

        for ($i = 0; $i < $count; $i++) {
            $username = strtolower(Str::random(8));
            
            // Ensure unique username
            while (Voucher::where('username', $username)->exists()) {
                $username = strtolower(Str::random(8));
            }

            Voucher::create([
                'username' => $username,
                'password' => strtolower(Str::random(8)),
                'pod_id' => 1,
                'status' => 'belum aktif',
                'duration_days' => $durationDays,
            ]);
        }

        return redirect()->back()->with('message', $count . ' vouchers generated successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:vouchers,username',
            'password' => 'required|string',
            'pod_id' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif,terbeli,belum aktif,expired',
            'duration_days' => 'required|integer',
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
            'duration_days' => 'required|integer',
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
