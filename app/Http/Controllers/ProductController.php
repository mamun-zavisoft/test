<?php

namespace App\Http\Controllers;

use App\Actions\FetchProduct;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Zone;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = (new FetchProduct)->execute($request);
        if ($request->ajax()) {
            return view('components.products.table', ['entity' => $products])->render();
        }

        return view('backend.products.index', compact('products'));
    }

    public function create()
    {
        $zones = Zone::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();

        return view('backend.products.create', compact('zones', 'brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|max:4000',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // dd($request->all());
        try {
            $product = Product::create([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price,
            ]);

            $product->thumbnail = $request->file('thumbnail');

            foreach($request->images as $image) {
                $product->images = $image;
            }

            $product->save();
          
            return response()->json(['message' => 'Product created successfully!', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function edit(Category $category)
    {
        $zones = Zone::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();

        return view('backend.categories.edit', compact('category', 'zones', 'brands', 'categories'));
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

            $category->image = $request->file('image');
            $category->save();

            return response()->json(['message' => 'Category updated successfully!', 'type' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
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
