<?php

namespace App\Http\Controllers;

use App\Actions\FetchVehicleFuel;
use App\Models\Vehicle;
use App\Models\VehicleFuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleFuelController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:vehicle-fuel-create')->only('create', 'store');
        $this->middleware('permission:vehicle-fuel-list')->only('index');
        $this->middleware('permission:vehicle-fuel-update')->only('edit', 'update');
        $this->middleware('permission:vehicle-fuel-destroy')->only('destroy');
    }

    /**
     * Display a listing of the vehicle fuel records.
     */
    public function index(Request $request)
    {
        $vehicleFuels = (new FetchVehicleFuel)->execute($request);

        if ($request->ajax()) {
            return view('components.vehicleFuels.table', ['entity' => $vehicleFuels])->render();
        }

        return view('backend.vehicle_fuels.index', ['title' => 'Vehicle Fuels'], compact('vehicleFuels'));
    }

    /**
     * Show the form for creating a new vehicle fuel entry.
     */
    public function create()
    {
        $vehicles = Vehicle::select('id', 'license_plate')->where('owner_type', 1)->get();
        $recentFuelings = VehicleFuel::with('vehicle')->latest()->take(5)->get();

        return view('backend.vehicle_fuels.create', get_defined_vars());
    }

    /**
     * Store a newly created vehicle fuel record in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'fuel_type' => 'required|integer|in:1,2,3',
            'current_odometer' => 'required|numeric',
            'fuel_qty' => 'required|numeric',
            'fuel_rate' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        try {
            $vehicle = Vehicle::find($request->vehicle_id);
            if ($request->current_odometer < $vehicle->current_odometer) {
                return response()->json(['message' => 'Current odometer must be greater than last odometer', 'type' => 'error']);
            }

            $last_odo = $vehicle->fuels()->latest()->first()?->current_odometer;

            if ($last_odo) {
                $distance = $request->current_odometer - $last_odo;
                $mileage = number_format($distance / $request->fuel_qty, 2);
            } else {
                $mileage = 0;
            }

            DB::beginTransaction();
            $vehicleFuel = VehicleFuel::create([
                'vehicle_id' => $request->vehicle_id,
                'fuel_type' => $request->fuel_type,
                'current_odometer' => $request->current_odometer,
                'fuel_qty' => $request->fuel_qty,
                'fuel_rate' => $request->fuel_rate,
                'total_price' => $request->fuel_qty * $request->fuel_rate,
                'mileage' => $mileage
            ]);
            $vehicle->update(['current_odometer' => $request->current_odometer, 'mileage' => $mileage]);

            DB::commit();

            $entity = VehicleFuel::with('vehicle')->latest()->take(5)->get();

            $latestFuelingsHtml = view('components.vehicleFuels.table', compact('entity'))->render();

            return response()->json(['message' => 'Fuel entry added successfully!', 'type' => 'success', 'latestFuelingsHtml' => $latestFuelingsHtml]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'type' => 'error']);
        }
    }

    /**
     * Show the form for editing an existing fuel record.
     */
    public function edit(VehicleFuel $vehicleFuel)
    {
        $vehicles = Vehicle::select('id', 'license_plate')->get();

        return view('backend.vehicle_fuels.edit', compact('vehicleFuel', 'vehicles'));
    }

    /**
     * Update the specified vehicle fuel record.
     */
    public function update(Request $request, VehicleFuel $vehicleFuel)
    {
        $request->validate([
            'vehicle_id' => 'required|integer',
            'fuel_type' => 'required|integer|in:1,2,3',
            'current_odometer' => 'required|numeric',
            'fuel_qty' => 'required|numeric',
            'fuel_rate' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        // Check if the current odometer is less than the last odometer
        if ($request->current_odometer < $vehicleFuel->vehicle->current_odometer) {
            return response()->json(['message' => 'Current odometer must be greater than last odometer', 'type' => 'error']);
        }

        $vehicleFuel->update([
            'vehicle_id' => $request->vehicle_id,
            'fuel_type' => $request->fuel_type,
            // 'current_odometer' => $request->current_odometer,
            'fuel_qty' => $request->fuel_qty,
            'fuel_rate' => $request->fuel_rate,
            'total_price' => $request->fuel_qty * $request->fuel_rate,
        ]);


        return response()->json(['message' => 'Fuel entry updated successfully!', 'type' => 'success', 'redirectUrl' => route('admin.vehicle-fuels.index')]);
    }

    /**
     * Remove the specified vehicle fuel record from storage.
     */
    public function destroy(VehicleFuel $vehicleFuel)
    {
        $vehicleFuel->delete();

        return redirect()->route('admin.vehicle-fuels.index')->with('success', 'Fuel entry deleted successfully.');
    }

    /** 
     * Get the current odometer reading of a vehicle.
     */
    public function getCurrentOdometer(Request $request)
    {
        $vehicle = Vehicle::with('vehicleModel')->where('id', $request->vehicle_id)->first();

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        return response()->json([
            'vehicle' => $vehicle,
            'vehicle_model' => $vehicle->vehicleModel,
        ]);
    }
}
