<?php

namespace App\Actions;

use App\Models\Product;

class FetchProduct
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Product::query()
            ->with('media', 'category:id,name', 'brand:id,name')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            // ->select('id', 'name', 'created_at')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
