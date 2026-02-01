<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Tag;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Taxonomies/Index', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);

        Category::create([
            'name' => ['es' => $data['name']], // Defaulting to ES for now as per simple input
            'slug' => $slug,
        ]);

        return back()->with('success', 'Categoría creada.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);

        $category->update([
            'name' => ['es' => $data['name']],
            'slug' => $slug,
        ]);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}
