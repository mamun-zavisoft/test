<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function drawer()
    {
        return $this->belongsTo(Drawer::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}
