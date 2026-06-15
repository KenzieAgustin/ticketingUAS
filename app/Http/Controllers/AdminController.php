<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', Rule::in(['admin', 'staff_gate', 'customer'])],
        ]);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['role' => 'Tidak bisa mengubah role diri sendiri.']);
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Role {$user->name} berhasil diubah ke {$request->role}.");
    }

    public function activities()
    {
        $activities = UserActivity::with('user')
                        ->latest()
                        ->paginate(20);

        return view('admin.activities', compact('activities'));
    }
}
