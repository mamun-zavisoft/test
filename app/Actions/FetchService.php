<?php

namespace App\Actions;

use App\Models\Service;

class FetchService
{
    public function execute($request)
    {

        $search = request()->input('search', '');
        $perPage = request()->input('per_page', 10);
        $serviceType = request()->input('serviceType', '');

        return Service::query()
            ->with('vehicle:id,license_plate', 'account:id,type', 'sale')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhere('service_type', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhere('paid_status', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', function ($query) use ($search) {
                        $query->where('license_plate', 'like', "%{$search}%");
                    });
                });
            })
            ->when($serviceType, function ($query, $serviceType) {
                $query->where('service_type', $serviceType);
            })
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
