<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

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

    public function manualActivate(Voucher $voucher)
    {
        $apiUrl = env('PNETLAB_API_URL', 'http://domain.com/bulkuser/api/v2/users');
        $apiKey = env('PNETLAB_API_KEY', 'YOUR_API_KEY_HERE');

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post($apiUrl, [
                'username' => $voucher->username,
                'password' => $voucher->password,
                'name' => $voucher->username,
                'expired_days' => $voucher->duration_days
            ]);

            if ($response->successful()) {
                $voucher->update([
                    'status' => 'aktif',
                    'activated_at' => now(),
                    'expired_at' => now()->addDays($voucher->duration_days)
                ]);
                return back()->with('message', 'User berhasil diaktivasi di server PNETLab.');
            } else {
                return back()->withErrors(['api' => 'Gagal aktivasi API: ' . $response->body()]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Koneksi API gagal: ' . $e->getMessage()]);
        }
    }

    public function manualBlock(Voucher $voucher)
    {
        $baseUrl = rtrim(env('PNETLAB_API_URL', 'http://domain.com/bulkuser/api/v2/users'), '/');
        // Because the API URL in .env is usually .../users, we append the username and block
        $blockUrl = $baseUrl . '/' . urlencode($voucher->username) . '/block';
        $apiKey = env('PNETLAB_API_KEY', 'YOUR_API_KEY_HERE');

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
            ])->put($blockUrl);

            if ($response->successful()) {
                $voucher->update(['status' => 'nonaktif']);
                return back()->with('message', 'User berhasil diblokir di server PNETLab.');
            } else {
                return back()->withErrors(['api' => 'Gagal block API: ' . $response->body()]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Koneksi API gagal: ' . $e->getMessage()]);
        }
    }
}
