<?php

namespace App\Actions;

use App\Models\Zone;

class FetchZone
{

    public function excute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Zone::query()
        ->when($search, function($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");

        })
        ->select('id','name','location','phone','created_at')
        ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

    }




}