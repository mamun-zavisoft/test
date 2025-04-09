<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\VehicleFuel;
use Illuminate\Http\Request;

class VehicleReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to last 7 days if no dates provided
        $startDate = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        // Get vehicles with owner_type information
        $vehicles = Vehicle::select('id', 'license_plate', 'owner_type')->get(); 
        
        return view('backend.vehicles.report', get_defined_vars());
    }

    public function report(Request $request)
    {
        // Default to last 7 days if no dates provided
        $startDate = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = Service::query()
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->selectRaw('vehicle_id, COUNT(*) as service_count, SUM(grand_total) as total_cost')
            ->groupBy('vehicle_id')
            ->with(['vehicle' => function($q) {
                $q->select('id', 'license_plate', 'owner_type');
            }])
            ->orderBy('total_cost', 'desc');

        // Filter by vehicle ID if provided
        if ($request->has('vehicle_id') && !empty($request->vehicle_id)) {
            $query->where('vehicle_id', $request->vehicle_id);
        }
        
        // Filter by vehicle type if provided
        if ($request->has('vehicle_type') && !empty($request->vehicle_type)) {
            $vehicleIds = Vehicle::where('owner_type', $request->vehicle_type)
                         ->pluck('id')
                         ->toArray();
            $query->whereIn('vehicle_id', $vehicleIds);
        }

        $services = $query->get();

        // Fuel data for the same period
        $fuelQuery = VehicleFuel::query()
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);

        // Filter by vehicle ID if provided
        if ($request->has('vehicle_id') && !empty($request->vehicle_id)) {
            $fuelQuery->where('vehicle_id', $request->vehicle_id);
        }
        
        // Filter by vehicle type if provided
        if ($request->has('vehicle_type') && !empty($request->vehicle_type)) {
            $vehicleIds = Vehicle::where('owner_type', $request->vehicle_type)
                         ->pluck('id')
                         ->toArray();
            $fuelQuery->whereIn('vehicle_id', $vehicleIds);
        }

        $fuelData = $fuelQuery->selectRaw('SUM(fuel_qty) as total_fuel_qty, SUM(total_price) as total_fuel_cost')
            ->first();

        // For AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'services' => $services,
                'fuelData' => $fuelData,
                'summary' => [
                    'total_vehicles' => $services->count(),
                    'total_service_cost' => $services->sum('total_cost'),
                    'total_fuel_qty' => $fuelData->total_fuel_qty ?? 0,
                    'total_fuel_cost' => $fuelData->total_fuel_cost ?? 0,
                ]
            ]);
        }

        return view('backend.vehicles.report', compact('services', 'fuelData', 'startDate', 'endDate'));
    }
}
