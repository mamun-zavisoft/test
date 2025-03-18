<?php

namespace App\Actions;

use App\Models\Drawer;

class FetchDrawer
{
    public function execute($request){

        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $rack_id = $request->input('rack_id', '');

        return Drawer::query()
            ->with('rack:id,name')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
                $query->orWhereHas('rack', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->when($rack_id, function ($query) use ($rack_id) {
                $query->where('rack_id', $rack_id);
            })
           ->select('id', 'name', 'rack_id', 'created_at')
           ->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }
}