<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?? '🍰',
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan! 🎉');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?? $category->icon,
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui! ✨');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus! 🗑️');
    }
}
