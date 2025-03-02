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

    public function ProductCount($id) {
        $availableCount = DB::table('stock_histories')
            ->whereIn('stock_purchase_id', function($query) use ($id) {
                $query->select('id')
                    ->from('stock_purchases')
                    ->where('drawer_id', $id);
            })
            ->whereNull('sale_id')
            ->count();
        
        return $availableCount;
    }
}
