<?php

namespace App\Http\Controllers;

use App\Models\PnetlabUser;
use Illuminate\Http\Request;

class PnetlabUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('PnetlabUser/Index', [
            'users' => PnetlabUser::orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cari POD ID yang sudah digunakan
        $usedPods = PnetlabUser::whereNotNull('pod')->pluck('pod')->sort()->toArray();
        
        // Cari nomor POD terkecil yang belum digunakan (mulai dari 0)
        $nextPod = 0;
        while (in_array($nextPod, $usedPods)) {
            $nextPod++;
        }

        return inertia('PnetlabUser/Create', [
            'nextPod' => $nextPod
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:pnetlab_users',
            'name' => 'required|string',
            'email' => 'nullable|email',
            'pod' => 'required|integer',
            'role' => 'required|integer',
            'expired_time' => 'nullable|date',
        ]);

        PnetlabUser::create($validated);

        return redirect()->route('pnetlab-users.index')->with('message', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PnetlabUser $pnetlabUser)
    {
        return inertia('PnetlabUser/Edit', [
            'pnetlabUser' => $pnetlabUser
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PnetlabUser $pnetlabUser)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:pnetlab_users,username,' . $pnetlabUser->id,
            'name' => 'required|string',
            'email' => 'nullable|email',
            'pod' => 'required|integer',
            'role' => 'required|integer',
            'expired_time' => 'nullable|date',
        ]);

        $pnetlabUser->update($validated);

        return redirect()->route('pnetlab-users.index')->with('message', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PnetlabUser $pnetlabUser)
    {
        $pnetlabUser->delete();

        return redirect()->route('pnetlab-users.index')->with('message', 'User deleted successfully.');
    }
}
