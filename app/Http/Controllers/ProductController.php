<?php

namespace App\Http\Controllers;

use App\Actions\FetchProduct;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product-list')->only('index');
        $this->middleware('permission:product-create')->only('create', 'store');
        $this->middleware('permission:product-update')->only('edit', 'update');
        $this->middleware('permission:product-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $products = (new FetchProduct)->execute($request);
        $categories = Category::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();

        if ($request->ajax()) {
            return view('components.products.table', ['products' => $products])->render();
        }

        return view('backend.products.index', ['title' => 'Products'], compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $brands = Brand::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();

        return view('backend.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|max:4000',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'thumbnail' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],[
            'thumbnails.mimes' => 'The thumbnail must be a file of type: jpeg, png, jpg, gif, svg.',
            'images.*.mimes' => 'The images must be a file of type: jpeg, png, jpg, gif, svg.',
        ]);
        try {
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price,
                'zone_id' => auth()->user()?->zone_id,
            ]);

            $product->thumbnail = $request->file('thumbnail');

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->images = $image;
                }
            }

            $product->save();

            DB::commit();

            return response()->json(['message' => 'Product created successfully!', 'type' => 'success', 'redirect' => route('admin.products.index')], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function edit(Product $product)
    {
        $brands = Brand::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();

        return view('backend.products.edit', ['title' => 'Edit Product'], compact('brands', 'categories', 'product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'name' => 'required|string|max:255|unique:products,name,'.$product->id,
            'description' => 'nullable|string|max:4000',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            DB::beginTransaction();
            $product->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price,
                'zone_id' => auth()->user()?->zone_id,
            ]);

            $product->thumbnail = $request->file('thumbnail');           

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->images = $image;
                }
            }

            $product->save();

            DB::commit();

            return response()->json(['message' => 'Product Updated successfully!', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function destroy(Product $product)
    {
        if ($product->getTotalAvailableQuantity() > 0) {
            return redirect()->back()->with('error', 'Product has stock, cannot be deleted');
        }

        if ($product->purchaseDetails()->count() > 0) {
            return redirect()->back()->with('error', 'Product has purchases, cannot be deleted');
        }
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }

    public function search(Request $request)
    {
        $search = $request->search ?? '';
        $query = Product::query();

        if ($search !== '') {
            $query->where('name', 'LIKE', '%'.$search.'%');
        }
        $products = $query->orderBy('id', 'desc')->limit(50)->get();
        $productCount = $products->count();

        if ($productCount === 0) {
            return '<h5>Product Not Found</h5>';
        } else {
            return view('backend.products.search_list', compact('products'));
        }
    }
}
