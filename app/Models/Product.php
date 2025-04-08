<?php

namespace App\Models;

use App\Media\HasMedia;
use App\Media\Mediable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model implements Mediable
{
    use HasMedia;

    protected $guarded = [];

    protected $appends = ['thumbnail', 'images', 'total_available_quantity'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'product_id');
    }
    

    public function setThumbnailAttribute($file)
    {
        if ($file) {
            $existingMedia = $this->media()->where('collection_name', 'thumbnail')->first();
            if ($existingMedia) {
                $this->deleteMedia($existingMedia->id);
            }

            $this->addMedia($file, 'thumbnail', []);
        }
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstUrl('thumbnail');
    }

    public function setImagesAttribute($file)
    {
        if ($file) {
            $this->addMedia($file, 'images', []);
        }
    }

    public function getImagesAttribute()
    {
        return $this->getUrl('images');
    }

    public function stockPurchases()
    {
        return $this->hasMany(StockPurchase::class, 'product_id');
    }

    /**
     * Get the stock histories for this product through stock purchases
     */
    public function stockHistories()
    {
        return $this->hasManyThrough(
            StockHistory::class,
            StockPurchase::class,
            'product_id',   // Foreign key on stock_purchases table
            'stock_purchase_id', // Foreign key on stock_histories table
            'id',           // Local key on products table
            'id'            // Local key on stock_purchases table
        );
    }

    /**
     * Get total available quantity for this product across all locations
     *
     * @return int Total available quantity
     */
    public function getTotalAvailableQuantity()
    {
        $availableCount = DB::table('stock_histories')
            ->whereIn('stock_purchase_id', function ($query) {
                $query->select('id')
                    ->from('stock_purchases')
                    ->where('product_id', $this->id);
            })
            ->whereNull('sale_id')
            ->count();

        return $availableCount;
    }

    public function getTotalAvailableQuantityAttribute()
    {
        return $this->getTotalAvailableQuantity();
    }

    /**
     * Get available quantity for this product in a specific drawer
     *
     * @param  int  $rackId  Rack ID
     * @param  int  $drawerId  Drawer ID
     * @return int Available quantity in the specified drawer
     */
    public function getDrawerAvailableQuantity($drawerId)
    {
        // Count available items in a single query using a subquery
        $availableCount = DB::table('stock_histories')
            ->whereIn('stock_purchase_id', function ($query) use ($drawerId) {
                $query->select('id')
                    ->from('stock_purchases')
                    ->where('product_id', $this->id)
                    ->where('drawer_id', $drawerId);
            })
            ->whereNull('sale_id')
            ->count();

        return $availableCount;
    }

    /**
     * Get all racks that contain available quantities of this product
     *
     * @return \Illuminate\Support\Collection Collection of racks with available quantity
     */
    public function getAvailableRacks()
    {
        return DB::table('racks')
            ->select('racks.id', 'racks.name', DB::raw('COUNT(stock_histories.id) as available_quantity'))
            ->join('drawers', 'racks.id', '=', 'drawers.rack_id')
            ->join('stock_purchases', 'drawers.id', '=', 'stock_purchases.drawer_id')
            ->join('stock_histories', 'stock_purchases.id', '=', 'stock_histories.stock_purchase_id')
            ->where('stock_purchases.product_id', $this->id)
            ->whereNull('stock_histories.sale_id')
            ->groupBy('racks.id', 'racks.name')
            ->having('available_quantity', '>', 0)
            ->orderBy('racks.name')
            ->get();
    }

    /**
     * Get all drawers in a specific rack that contain available quantities of this product
     */
    public function getDrawersInRack($rackId)
    {
        return DB::table('drawers')
            ->select('drawers.id', 'drawers.name', DB::raw('COUNT(stock_histories.id) as available_quantity'))
            ->join('stock_purchases', 'drawers.id', '=', 'stock_purchases.drawer_id')
            ->join('stock_histories', 'stock_purchases.id', '=', 'stock_histories.stock_purchase_id')
            ->where('stock_purchases.product_id', $this->id)
            ->where('drawers.rack_id', $rackId)
            ->whereNull('stock_histories.sale_id')
            ->groupBy('drawers.id', 'drawers.name')
            ->having('available_quantity', '>', 0)
            ->orderBy('drawers.name')
            ->get();
    }
}
