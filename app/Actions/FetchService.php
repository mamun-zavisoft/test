<?php

namespace App\Actions;

use App\Models\Service;

class FetchService
{
    public function execute($request){

        $search = request()->input('search', '');
        $perPage = request()->input('per_page', 10);

        return Service::query()
            ->with('vehicle:id,license_plate','account:id,type','sale')
            ->when($search, function($query, $search) {
                $query->where('service_type', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhere('paid_status', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', function ($query) use ($search) {
                        $query->where('license_plate', 'like', "%{$search}%");
                    });
            })
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}