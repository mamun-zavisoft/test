<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function purchase(){
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function sale(){
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }
}
