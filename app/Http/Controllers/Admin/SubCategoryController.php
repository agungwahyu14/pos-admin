<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->latest()->get();
        return view('admin.subcategories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        SubCategory::create($validated);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub-Category created successfully.');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        return view('admin.subcategories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $subCategory->update($validated);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub-Category updated successfully.');
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub-Category deleted successfully.');
    }
}
