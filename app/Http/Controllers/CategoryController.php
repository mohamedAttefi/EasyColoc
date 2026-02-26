<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $colocation = $this->requireOwnerColocation();
        $categories = $colocation->categories()->orderBy('name')->get();

        return view('categories.index', compact('colocation', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $colocation = $this->requireOwnerColocation();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,colocation_id,'.$colocation->id,
            'color' => 'nullable|string|max:20|regex:/^[a-z-]+$/',
            'icon' => 'nullable|string|max:50|regex:/^[a-z_]+$/',
        ]);

        Category::create([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? 'slate',
            'icon' => $validated['icon'] ?? 'more_horiz',
            'colocation_id' => $colocation->id,
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        $colocation = $this->requireOwnerColocation();

        if ($category->colocation_id !== $colocation->id) {
            abort(403, 'Unauthorized access to this category.');
        }

        return view('categories.edit', compact('colocation', 'category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $colocation = $this->requireOwnerColocation();

        if ($category->colocation_id !== $colocation->id) {
            abort(403, 'Unauthorized access to this category.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id.',id,colocation_id,'.$colocation->id,
            'color' => 'nullable|string|max:20|regex:/^[a-z-]+$/',
            'icon' => 'nullable|string|max:50|regex:/^[a-z_]+$/',
        ]);

        $category->update([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? $category->color,
            'icon' => $validated['icon'] ?? $category->icon,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $colocation = $this->requireOwnerColocation();

        if ($category->colocation_id !== $colocation->id) {
            abort(403, 'Unauthorized access to this category.');
        }

        if ($category->expenses()->exists()) {
            return back()->with('error', 'Cannot delete a category that has expenses.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }

    private function requireOwnerColocation(): Colocation
    {
        $user = Auth::user();
        $colocation = $user->activeColocation();

        if (!$colocation) {
            abort(403, 'You need to join a colocation first.');
        }

        if (!$colocation->isOwnerOf($user)) {
            abort(403, 'Only owners can manage categories.');
        }

        return $colocation;
    }
}
