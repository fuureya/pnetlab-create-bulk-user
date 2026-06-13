<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PendaftarController extends Controller
{
    public function index()
    {
        $pendaftars = User::latest()->paginate(10);
        return Inertia::render('Pendaftar', [
            'pendaftars' => $pendaftars
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'role' => 'required|string|in:admin,user',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('message', 'Pendaftar created successfully.');
    }

    public function update(Request $request, User $pendaftar)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class.',id,'.$pendaftar->id,
            'role' => 'required|string|in:admin,user',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $pendaftar->name = $request->name;
        $pendaftar->email = $request->email;
        $pendaftar->role = $request->role;
        
        if ($request->filled('password')) {
            $pendaftar->password = Hash::make($request->password);
        }

        $pendaftar->save();

        return redirect()->back()->with('message', 'Pendaftar updated successfully.');
    }

    public function destroy(User $pendaftar)
    {
        $pendaftar->delete();

        return redirect()->back()->with('message', 'Pendaftar deleted successfully.');
    }
}
