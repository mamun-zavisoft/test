<?php

namespace App\Actions;

use App\Models\ServiceDetail;

class FetchServiceDetail
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $parPage = $request->input('per_page', 10);

        return  ServiceDetail::query()
            ->with('service', 'serviceChart')
            ->when($search, function($query, $search) {
                $query->where('service_id', 'like', "%{$search}%")
                ->orWhere('service_chart_id', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%");
            })
            ->select('id','service_id','service_chart_id','price','created_at')
            ->orderBy('id','desc')->paginate($parPage)->withQueryString();
    }
}