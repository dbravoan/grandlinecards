<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);

        Tag::create([
            'name' => ['es' => $data['name']],
            'slug' => $slug,
        ]);

        return back()->with('success', 'Etiqueta creada.');
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);

        $tag->update([
            'name' => ['es' => $data['name']],
            'slug' => $slug,
        ]);

        return back()->with('success', 'Etiqueta actualizada.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Etiqueta eliminada.');
    }
}
