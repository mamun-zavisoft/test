<?php

namespace App\Actions;

use App\Models\Vehicle;

class FetchVehicle
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Vehicle::query()
            ->with('zone')
            ->when($search, function($query, $search) {
                $query->where('owner_type', 'like', "%{$search}%")
                    ->orWhere('license_plate', 'like', "%{$search}%")
                    ->orWhere('zone_id', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->select('id','owner_type','license_plate','zone_id','status','created_at')
            ->orderBy('id','desc')->paginate($perPage)->withQueryString();
    }

}