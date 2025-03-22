<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Drawer extends Model
{
    protected $guarded = [];

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function ProductCount($id)
    {
        $availableCount = DB::table('stock_histories')
            ->whereIn('stock_purchase_id', function ($query) use ($id) {
                $query->select('id')
                    ->from('stock_purchases')
                    ->where('drawer_id', $id);
            })
            ->whereNull('sale_id')
            ->count();

        return $availableCount;
    }

    /**
     * Get all stock purchases associated with this drawer
     */
    public function stockPurchases()
    {
        return $this->hasMany(StockPurchase::class);
    }

    /**
     * Get available products (not sold) in this drawer with their quantities
     */
    public function getAvailableProductsAttribute()
    {
        // Get product quantities in this drawer that haven't been sold
        $productQuantities = DB::table('stock_purchases as sp')
            ->select(
                'products.id',
                'products.name',
                DB::raw('COUNT(sp.id) as available_quantity')
            )
            ->leftJoin('products', 'products.id', '=', 'sp.product_id')
            ->leftJoin('stock_histories as sh', 'sp.id', '=', 'sh.stock_purchase_id')
            ->where('sp.drawer_id', $this->id)
            ->where(function ($query) {
                $query->whereNull('sh.id')
                    ->orWhere('sh.sale_id', null);
            })
            ->groupBy('products.id', 'products.name')
            ->get();

        // Convert to collection of Product models with quantity attribute
        return $productQuantities->map(function ($item) {
            $product = Product::find($item->id);
            if ($product) {
                $product->available_quantity = $item->available_quantity;
            }
            return $product;
        })->filter();
    }

    /**
     * Get count of available products in this drawer
     */
    public function getAvailableProductsCountAttribute()
    {
        // Return the preloaded count if available
        if (isset($this->attributes['available_products_count'])) {
            return $this->attributes['available_products_count'];
        }

        // Fallback to the original query if not preloaded
        return DB::table('stock_purchases as sp')
            ->leftJoin('stock_histories as sh', 'sp.id', '=', 'sh.stock_purchase_id')
            ->where('sp.drawer_id', $this->id)
            ->where(function ($query) {
                $query->whereNull('sh.id')
                    ->orWhereNull('sh.sale_id');
            })
            ->count();
    }
}
