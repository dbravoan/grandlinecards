<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EventSuggestion;
use Illuminate\Http\Request;

class EventSuggestionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'event_date' => 'nullable|date',
            'description' => 'nullable|string'
        ]);

        $suggestion = EventSuggestion::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Sugerencia enviada correctamente.',
            'data' => $suggestion
        ], 201);
    }
}
