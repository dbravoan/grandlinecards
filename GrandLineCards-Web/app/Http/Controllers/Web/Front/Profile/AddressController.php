<?php

namespace App\Http\Controllers\Web\Front\Profile;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        return Inertia::render('Profile/Addresses', [
            'addresses' => Auth::user()->addresses()->orderByDesc('is_default')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'line_1' => 'required|string|max:255',
            'line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'province' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean'
        ]);

        $user = Auth::user();

        if ($validated['is_default'] ?? false) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'line_1' => 'required|string|max:255',
            'line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'province' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean'
        ]);

        if ($validated['is_default'] ?? false) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->back();
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->back();
    }
}
