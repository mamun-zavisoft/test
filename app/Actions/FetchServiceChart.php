<?php

namespace App\Actions;

use App\Models\ServiceChart;

class FetchServiceChart
{

    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return ServiceChart::query()
            ->when($search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
            })
            ->select('id','name','price','description','code','created_at')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

    }

}