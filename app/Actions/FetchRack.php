<?php

namespace App\Actions;

use App\Models\Rack;

class FetchRack
{
    public function execute($request)
    {

        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Rack::query()
            ->with('zone:id,name',
                    'drawers:id,name,rack_id',)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'zone_id', 'created_at')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
