<?php

namespace App\Http\Controllers;

use App\Actions\FetchCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
       $categories = (new FetchCategory)->execute($request);
        if ($request->ajax()) {
            return view('components.category.table', ['entity' => $categories])->render();
        }

        return view('backend.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories,name',
        ]);
        try {
            $category = Category::create([
                'name' => $request->name,
            ]);
    
            return response()->json(['message' => 'Category created successfully!', 'type' => 'success' ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error' ]);
        }

        
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:brands,name,' . $category->id,
        ]);

        try {
            $category->update([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'Category updated successfully!', 'type' => 'success' ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error' ]);
        }

    }

    public function destroy(Category $category)
    {
        // if ($category->products()->exists()) {
        //     return response()->json(['message' => 'Category has products, cannot delete!'], 422);
        // }
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully!');
        // return response()->json(['message' => 'Brand deleted successfully!']);
    }
}

