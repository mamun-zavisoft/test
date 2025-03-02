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
}
