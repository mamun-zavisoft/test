<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];

    public static $FULL_DUE = 1;
    public static $PARTIAL_PAID = 2;
    public static $FULL_PAID = 3;
    public static $IN_HOUSE = 4;

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeOnlySales($query)
    {
        return $query->where('type', 'only_sale');
    }

    public function scopeSelf($query)
    {
        return $query->where('type', 'self');
    }

    public function scopeExternal($query)
    {
        return $query->where('type', 'external');
    }

   public function service()
   {
       return $this->hasOne(Service::class);
   }

   public function products()
   {
       return $this->hasManyThrough(Product::class, SaleDetail::class, 'sale_id', 'id', 'id', 'product_id');
   }

   public function account()
   {
       return $this->belongsTo(Account::class);
   }
}
