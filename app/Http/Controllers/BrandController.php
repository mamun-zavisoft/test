<?php

namespace App\Http\Controllers;

use App\Actions\FetchBrand;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = (new FetchBrand)->execute($request);
        if ($request->ajax()) {
            return view('components.brands.table', ['brands' => $brands])->render();
        }

        return view('backend.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:brands,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            DB::beginTransaction();
            $brand = Brand::create([
                'name' => $request->name,
            ]);
            $brand->image = $request->file('image');
            $brand->save();

            DB::commit();

            return response()->json(['message' => 'Brand created successfully!', 'type' => 'success', 'brand' => $brand], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage().'1', 'type' => 'error']);
        }
    }

    public function update(Request $request, Brand $brand)
    {

        $request->validate([
            'name' => 'required|string|max:50|unique:brands,name,'.$brand->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $brand->update([
                'name' => $request->name,
            ]);

            $brand->image = $request->file('image');
            $brand->save();

            return response()->json(['message' => 'Brand updated successfully!', 'type' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function destroy(Brand $brand)
    {
        // if ($brand->products()->exists()) {
        //     return response()->json(['message' => 'Brand has products, cannot delete!'], 422);
        // }
        $brand->delete();

        return redirect()->back()->with('success', 'Brand deleted successfully!');
        // return response()->json(['message' => 'Brand deleted successfully!']);
    }

    public function status_change(Request $request, $id)
    {
        try {
            $brand = Brand::find($id)->update(['status' => $request->status]);

            return response()->json(['message' => 'Status updated successfully!', 'type' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'type' => 'error'], 500);
        }
    }
}
