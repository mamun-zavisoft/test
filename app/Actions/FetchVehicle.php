<?php

namespace App\Actions;

use App\Models\Vehicle;

class FetchVehicle
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $type = $request->input('vehicle_type', '');
        $typeValue = $type === 'self' ? '1' : '2';
        $zone_id = $request->input('zone_id', '');
        return Vehicle::query()
            ->with('zone')
            ->when($search, function ($query) use ($search) {
                $query->whereAny(['license_plate', 'zone_id', 'status'], 'like', "%{$search}%");
            })
            ->when($type, function ($query) use ($typeValue) {
                $query->where('owner_type', $typeValue);
            })
            ->when($zone_id, function ($query) use ($zone_id) {
                $query->where('zone_id', $zone_id);
            })
            ->select('id','owner_type','license_plate','zone_id','status','created_at')
            ->orderBy('id','desc')->paginate($perPage)->withQueryString();
            
    }

}