<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    public function fuels()
    {
        return $this->hasMany(VehicleFuel::class);
    }

    public function getAverageMileageAttribute()
    {
        $avgMileage = $this->fuels()->where('mileage', '>', 0)->avg('mileage');
        return number_format($avgMileage, 2);
    }

    /**
     * Get document validity status and message
     * 
     * @param string|null $date
     * @return array ['status' => 'expired|warning|valid', 'message' => '', 'days' => int]
     */
    public function getDocumentStatus($date)
    {
        if (!$date) {
            return ['status' => 'valid', 'message' => '', 'days' => 0];
        }

        $documentDate = Carbon::parse($date);
        $today = Carbon::today()->startOfDay();
        $daysDifference = $today->diffInDays($documentDate, false);

        if ($daysDifference < 0) {
            $daysExpired = abs($daysDifference);
            return [
                'status' => 'expired',
                'message' => "Expired {$daysExpired} days ago",
                'days' => $daysExpired
            ];
        } elseif ($daysDifference <= 30) {
            return [
                'status' => 'warning',
                'message' => "Expires in {$daysDifference} days",
                'days' => $daysDifference
            ];
        } else {
            return [
                'status' => 'valid',
                'message' => '',
                'days' => $daysDifference
            ];
        }
    }

    /**
     * Get all document statuses
     * 
     * @return array
     */
    public function getDocumentStatuses()
    {
        return [
            'registration' => $this->getDocumentStatus($this->registration_validity),
            'tax_token' => $this->getDocumentStatus($this->tax_token_validity),
            'fitness' => $this->getDocumentStatus($this->fitness_validity),
            'road_permit' => $this->getDocumentStatus($this->road_permit_validity),
            'insurance' => $this->getDocumentStatus($this->insurance_validity),
        ];
    }
}
