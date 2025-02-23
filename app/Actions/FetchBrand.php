<?php

namespace App\Actions;

use App\Models\Brand;

class FetchBrand
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Brand::query()
            ->with('media')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'status', 'created_at')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
