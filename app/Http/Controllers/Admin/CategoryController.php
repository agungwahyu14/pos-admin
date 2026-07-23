<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Category::create($data);

        Log::info('Admin: Category created', [
            'name' => $data['name'],
            'admin_id' => auth()->id()
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        Log::info('Admin: Category updated', [
            'category_id' => $category->id,
            'name' => $category->name,
            'admin_id' => auth()->id()
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        // Prevent deleting category if it has products (assuming relationship products() exists)
        if ($category->products()->count() > 0) {
            Log::warning('Admin: Failed to delete category (has products)', [
                'category_id' => $category->id,
                'admin_id' => auth()->id()
            ]);
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with associated products.');
        }
        
        $categoryId = $category->id;
        $category->delete();

        Log::info('Admin: Category deleted', [
            'category_id' => $categoryId,
            'admin_id' => auth()->id()
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
