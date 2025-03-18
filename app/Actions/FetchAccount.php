<?php

namespace App\Actions;

use App\Models\Account;

class FetchAccount
{

    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $type = $request->input('account_type', '');
        $account_type = $type === 'cash' ? '1' : '2';

        return Account::query()
            ->when($search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('balance', 'like', "%{$search}%");
            })
            ->when($account_type, function($query) use($account_type) {
                $query->where('type', $account_type);
            })
            ->select('id','name','type','balance','created_at')
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}