<?php

namespace App\Actions;

use App\Models\Hub;

class FetchHub
{
    public function execute($request){

        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Hub::query()
            ->with('zone:id,name')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('custom_hub_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            })
            ->select('id', 'zone_id', 'name', 'custom_hub_id', 'phone', 'address')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}