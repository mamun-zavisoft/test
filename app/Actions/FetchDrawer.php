<?php

namespace App\Actions;

use App\Models\Drawer;
use Illuminate\Support\Facades\DB;

class FetchDrawer
{
    public function execute($request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        // Get drawers with rack relationship
        $drawers = Drawer::query()
            ->with('rack:id,name')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
                $query->orWhereHas('rack', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->select('id', 'name', 'rack_id', 'created_at')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Get drawer IDs
        $drawerIds = $drawers->pluck('id')->toArray();

        // 1. Get product counts for all drawers in a single query
        $productCounts = DB::table('stock_purchases as sp')
            ->select('sp.drawer_id', DB::raw('COUNT(sp.id) as count'))
            ->leftJoin('stock_histories as sh', 'sp.id', '=', 'sh.stock_purchase_id')
            ->whereIn('sp.drawer_id', $drawerIds)
            ->where(function ($query) {
                $query->whereNull('sh.id')
                    ->orWhereNull('sh.sale_id');
            })
            ->groupBy('sp.drawer_id')
            ->pluck('count', 'drawer_id');

        // 2. Get all available products for all drawers in a single query
        $productsByDrawer = DB::table('stock_purchases as sp')
            ->select(
                'sp.drawer_id',
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('COUNT(sp.id) as available_quantity')
            )
            ->join('products', 'products.id', '=', 'sp.product_id')
            ->leftJoin('stock_histories as sh', 'sp.id', '=', 'sh.stock_purchase_id')
            ->whereIn('sp.drawer_id', $drawerIds)
            ->where(function ($query) {
                $query->whereNull('sh.id')
                    ->orWhereNull('sh.sale_id');
            })
            ->groupBy('sp.drawer_id', 'products.id', 'products.name', )
            ->get()
            ->groupBy('drawer_id');

        // Attach data to drawer models
        foreach ($drawers as $drawer) {
            // Attach product count
            $drawer->available_products_count = $productCounts[$drawer->id] ?? 0;

            // Attach available products
            $products = collect($productsByDrawer[$drawer->id] ?? [])
                ->map(function ($item) {
                    // Create a standard object that mimics a Product model with available_quantity
                    $product = new \stdClass();
                    $product->id = $item->product_id;
                    $product->name = $item->product_name;
                    // $product->thumbnail = $item->thumbnail;
                    $product->available_quantity = $item->available_quantity;
                    return $product;
                });

            $drawer->available_products = $products;
        }

        return $drawers;
    }
}
