<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Services\Content\AiContentService;

class PostController extends Controller
{
    public function generateExcerpt(Request $request, AiContentService $ai)
    {
        $request->validate(['content' => 'required|string']);
        
        $excerpt = $ai->generateExcerpt($request->input('content'));
        
        return response()->json(['excerpt' => $excerpt]);
    }

    public function index()
    {
        $posts = Post::with(['category', 'translations'])->latest()->paginate(10);

        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Posts/Edit', [
            'post' => null,
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $slug = $data['slug'] ?? Str::slug($data['title']);
        
        // Ensure slug is unique if auto-generated
        if (!$data['slug']) {
             $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
             if ($count > 0) $slug .= "-{$count}";
        }

        $post = Post::create([
            'category_id' => $data['category_id'],
            'slug' => $slug,
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        $post->translation()->create([
            'locale' => app()->getLocale(),
            'title' => $data['title'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
        ]);

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return to_route('admin.posts.index')->with('success', 'Noticia creada correctamente.');
    }

    public function edit(Post $post)
    {
        $post->load(['category', 'tags', 'translation']);

        return Inertia::render('Admin/Posts/Edit', [
            'post' => $post,
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);
        
        $slug = $data['slug'] ?? ($post->slug ?: Str::slug($data['title']));

        $post->update([
            'category_id' => $data['category_id'],
            'slug' => $slug,
            'status' => $data['status'],
            'published_at' => ($data['status'] === 'published' && !$post->published_at) ? now() : $post->published_at,
        ]);

        $post->translation()->updateOrCreate(
            ['locale' => app()->getLocale()],
            [
                'title' => $data['title'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
            ]
        );

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return to_route('admin.posts.index')->with('success', 'Noticia actualizada.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Noticia eliminada.');
    }
}
