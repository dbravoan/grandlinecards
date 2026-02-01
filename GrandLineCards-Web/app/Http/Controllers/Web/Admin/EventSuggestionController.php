<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSuggestion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventSuggestionController extends Controller
{
    public function index()
    {
        $suggestions = EventSuggestion::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return Inertia::render('Admin/EventSuggestions', [
            'suggestions' => $suggestions
        ]);
    }

    public function update(Request $request, EventSuggestion $suggestion)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $suggestion->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Sugerencia actualizada correctamente.');
    }
}
