<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class ContentController extends Controller
{
    public function index(): Response
    {
        $posts = Post::published()
            ->with(['category', 'translation'])
            ->latest('published_at')
            ->paginate(12);

        // Transform for the frontend to match previous mock structure if needed, or update frontend.
        // Let's assume we pass the Paginator object.

        return Inertia::render('Content/Index', [
            'posts' => $posts
        ]);
    }

    public function show(string $slug): Response
    {
        $post = Post::published()
             ->where('slug', $slug)
             ->with(['category', 'tags', 'translation'])
             ->firstOrFail();

        return Inertia::render('Content/Show', [
            'post' => $post
        ]);
    }
}
