<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $guarded = [];

    public function stockPurchase()
    {
        return $this->belongsTo(StockPurchase::class);
    }
}
