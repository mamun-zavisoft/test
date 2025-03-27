<?php

namespace App\Actions;

use App\Models\Account;

class FetchAccount
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $type = $request->input('account_Type', '');
        $account_Type = ($type === 'cash') ? '1' : (($type === 'bank') ? '2' : '');


        return Account::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('balance', 'like', "%{$search}%");
                });
            })
            ->when($account_Type, function ($query) use ($account_Type) {
                $query->where('type', '=', $account_Type);
            })
            ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}
