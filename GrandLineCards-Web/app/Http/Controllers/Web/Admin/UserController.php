<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search']),
        ]);
    }

    public function toggleBan(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot ban yourself.');
        }

        if ($user->banned_at) {
            $user->update(['banned_at' => null]);
            $message = 'User unbanned.';
        } else {
            $user->update(['banned_at' => now()]);
            $message = 'User banned.';
        }

        return back()->with('success', $message);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user,moderator', // Simple role string for now or sync roles
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot change your own role.');
        }

        // Update simple column
        $user->update(['role' => $request->role]);

        // Sync Spatie role if needed
        // $user->syncRoles($request->role); 

        return back()->with('success', 'User role updated.');
    }
}
