<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,          // Target model
            PurchaseDetail::class,   // Intermediate model
            'purchase_id',           // Foreign key on the intermediate model
            'id',                    // Foreign key on the target model
            'id',                    // Local key on the purchase model
            'product_id'             // Local key on the intermediate model
        );
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
