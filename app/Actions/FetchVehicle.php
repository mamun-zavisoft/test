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
        $vehicle_model_id = $request->input('vehicle_model_id', '');
        $hub_id = $request->input('hub_id', '');

        return Vehicle::query()
            ->with('zone', 'vehicleModel', 'hub')
            ->when($search, function ($query) use ($search) {
                $query->whereAny(['license_plate', 'zone_id', 'status'], 'like', "%{$search}%");
            })
            ->when($type, function ($query) use ($typeValue) {
                $query->where('owner_type', $typeValue);
            })
            ->when($zone_id, function ($query) use ($zone_id) {
                $query->where('zone_id', $zone_id);
            })
            ->when($vehicle_model_id, function ($query) use ($vehicle_model_id) {
                $query->where('vehicle_model_id', $vehicle_model_id);
            })
            ->when($hub_id, function ($query) use ($hub_id) {
                $query->where('hub_id', $hub_id);
            })
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

    }
}
