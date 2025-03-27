<?php

namespace App\Actions;

use App\Models\Supplier;

class FetchSupplier
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $zone_id = $request->input('zone_id', '');

        return Supplier::query()
            ->with('zone')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($zone_id, function ($query) use ($zone_id) {
                $query->where('zone_id', $zone_id);
            })
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
