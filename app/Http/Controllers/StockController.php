<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function getRacksForProduct($productId)
    {
        // First, get all racks that have this product
        $racks = DB::table('stock_purchases')
            ->join('drawers', 'stock_purchases.drawer_id', '=', 'drawers.id')
            ->join('racks', 'drawers.rack_id', '=', 'racks.id')
            ->where('stock_purchases.product_id', $productId)
            ->select('racks.id', 'racks.name')
            ->distinct()
            ->get();

        $racksWithCounts = $racks->map(function ($rack) use ($productId) {
            // For each rack, get all drawers
            $drawerIds = DB::table('drawers')
                ->where('rack_id', $rack->id)
                ->pluck('id')
                ->toArray();

            // Get all stock purchases for this product in these drawers
            $stockPurchases = DB::table('stock_purchases')
                ->whereIn('drawer_id', $drawerIds)
                ->where('product_id', $productId)
                ->get();

            $availableCount = 0;

            // For each stock purchase, count available items
            foreach ($stockPurchases as $purchase) {
                // Count stock_histories rows where sale_id is NULL
                $availableForPurchase = DB::table('stock_histories')
                    ->where('stock_purchase_id', $purchase->id)
                    ->whereNull('sale_id')
                    ->count();

                $availableCount += $availableForPurchase;
            }

            return [
                'id' => $rack->id,
                'name' => $rack->name,
                'product_count' => $availableCount,
            ];
        })->filter(function ($rack) {
            // Only include racks with available stock
            return $rack['product_count'] > 0;
        })->values();

        return response()->json(['racks' => $racksWithCounts]);
    }

    public function getDrawersForRack($productId, $rackId)
    {
        // Get all drawers in the specified rack
        $drawers = DB::table('drawers')
            ->where('rack_id', $rackId)
            ->select('id', 'name')
            ->get();

        $drawersWithCounts = $drawers->map(function ($drawer) use ($productId) {
            // Get all stock purchases for this product in this drawer
            $stockPurchases = DB::table('stock_purchases')
                ->where('product_id', $productId)
                ->where('drawer_id', $drawer->id)
                ->get();

            $availableCount = 0;

            // For each stock purchase, count available items
            foreach ($stockPurchases as $purchase) {
                // Count stock_histories rows where sale_id is NULL
                $availableForPurchase = DB::table('stock_histories')
                    ->where('stock_purchase_id', $purchase->id)
                    ->whereNull('sale_id')
                    ->count();

                $availableCount += $availableForPurchase;
            }

            return [
                'id' => $drawer->id,
                'name' => $drawer->name,
                'product_count' => $availableCount,
            ];
        })->filter(function ($drawer) {
            // Only include drawers with available stock
            return $drawer['product_count'] > 0;
        })->values();

        return response()->json(['drawers' => $drawersWithCounts]);
    }

    public function getStockInfo($productId, $rackId, $drawerId)
    {
        // Get all stock purchases for this product in the selected drawer
        $stockPurchases = DB::table('stock_purchases')
            ->where('product_id', $productId)
            ->where('drawer_id', $drawerId)
            ->get();

        if ($stockPurchases->isEmpty()) {
            return response()->json(['stock' => null]);
        }

        // Collect all stock purchase IDs
        $stockPurchaseIds = $stockPurchases->pluck('id')->toArray();

        // Count available quantity across all purchases (rows with NULL sale_id)
        $availableQty = DB::table('stock_histories')
            ->whereIn('stock_purchase_id', $stockPurchaseIds)
            ->whereNull('sale_id')
            ->count();

        // Get the sale price (using the latest purchase price if multiple exist)
        // $latestPurchase = DB::table('stock_purchases')
        //     ->where('product_id', $productId)
        //     ->where('drawer_id', $drawerId)
        //     ->orderBy('created_at', 'desc')
        //     ->first();

        $product = DB::table('products')
            ->where('id', $productId)
            ->first();

        $stockInfo = [
            'available_qty' => $availableQty,
            'sale_price' => $product->sale_price,
        ];

        return response()->json(['stock' => $stockInfo]);
    }
}
