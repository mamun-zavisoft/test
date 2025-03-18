<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

    public function serviceDetails()
    {
        return $this->hasMany(ServiceDetail::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }
}
