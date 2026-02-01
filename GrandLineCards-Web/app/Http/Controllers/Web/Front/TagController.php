<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller
{
    public function show(string $slug): Response
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        // Load related entities
        // Currently only posts, but structured to support events later
        $posts = $tag->posts()
                     ->published()
                     ->with('translation')
                     ->latest('published_at')
                     ->get();

        // Mock events for now or empty
        $events = []; // $tag->events()->future()->get();

        return Inertia::render('Tags/Show', [
            'tag' => $tag,
            'posts' => $posts,
            'events' => $events
        ]);
    }
}
