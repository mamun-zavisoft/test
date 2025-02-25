<?php

namespace App\Actions;

use App\Models\Account;

class FetchAccount
{

    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        return Account::query()
            ->when($search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('balance', 'like', "%{$search}%");
            })
            ->select('id','name','type','balance','created_at')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}