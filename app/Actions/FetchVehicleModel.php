<?php

namespace App\Actions;

use App\Models\VehicleModel;

class FetchVehicleModel
{
    public function execute($request)
    {

        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return VehicleModel::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('manufacturer', 'like', "%{$search}%")
                    ->orWhere('engine_cc', 'like', "%{$search}%")
                    ->orWhere('fuel_capacity', 'like', "%{$search}%")
                    ->orWhere('payload_capacity', 'like', "%{$search}%")
                    ->orWhere('body_length', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'manufacturer', 'engine_cc', 'fuel_capacity', 'payload_capacity', 'body_length', 'avg_mileage','created_at')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
