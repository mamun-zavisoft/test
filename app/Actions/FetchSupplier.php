<?php

namespace App\Actions;

use App\Models\Supplier;

class FetchSupplier
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Supplier::query()
            ->with('zone')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
            })
            ->select('id', 'zone_id', 'name', 'phone', 'balance')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
