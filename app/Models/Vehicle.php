<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $guarded = [];

    public static $COVERED_VAN = 1;

    public static $MOTORBIKE = 2;

    public static $PICKUP = 3;

    public static $TRUCK = 4;

    public static $TBA = 5;

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }

    public function service()
    {
        return $this->hasMany(Service::class);
    }
}
