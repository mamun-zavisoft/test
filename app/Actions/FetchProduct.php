<?php

namespace App\Actions;

use App\Models\Product;

class FetchProduct
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $brand_id = $request->input('brand_id', '');
        $category_id = $request->input('category_id', '');

        return Product::query()
            ->with('media', 'category:id,name', 'brand:id,name')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($brand_id, function ($query, $brand_id) {
                $query->where('brand_id', $brand_id);
            })
            ->when($category_id, function ($query, $category_id) {
                $query->where('category_id', $category_id);
            })
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
