<?php

namespace App\Actions;

use App\Models\Sale;
use App\Models\Service;

class FetchSale
{
    public function execute($request){

        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Sale::query()
            ->with('account:id,name')
            ->when($search, function($query, $search) {
                $query->where('account_id', 'like', "%{$search}%")
                        ->orWhere('transaction_id', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}