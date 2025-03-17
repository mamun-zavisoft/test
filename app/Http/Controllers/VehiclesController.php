<?php

namespace App\Http\Controllers;

use App\Actions\FetchVehicle;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller
{
    
    public function index(Request $request)
    {
        $vehicles = (new FetchVehicle)->execute($request);
        $zones = Zone::select('id', 'name')->get();

        if ($request->ajax()){
            return view('components.vehicles.table', ['entity' => $vehicles])->render();
        }
        return view('backend.vehicles.index', compact('vehicles', 'zones'));
    }


    public function store(Request $request)
    {
        try{

            $request->validate([
                'owner_type' => 'required|in:1,2',
                'license_plate' => 'required|string|max:50|unique:vehicles,license_plate',
                'status' => 'required|in:1,2'
            ]);

            DB::beginTransaction();

            $vehicle = Vehicle::create([
                'owner_type' => $request->owner_type,
                'license_plate' => $request->license_plate,
                'zone_id' => auth()->user()?->zone_id,
                'status' => $request->status
            ]);

            DB::commit();

            return response()->json(['message' => 'Vehicle created successfully!', 'type' => 'success', 'data' => $vehicle ],200);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }


    public function show(Vehicle $vehicle){

        $services = Service::where('vehicle_id', $vehicle->id)->get();
        return view('backend.vehicles.show',compact('vehicle','services',));
    }


    public function update(Request $request, Vehicle $vehicle)
    {
        try{
            $request->validate([
                'owner_type' => 'required|in:1,2',
                'license_plate' => 'required|string|max:50|unique:vehicles,license_plate,' . $vehicle->id,
                'status' => 'required|in:1,2'
            ]);


            $vehicle->update([
                'owner_type' => $request->owner_type,
                'license_plate' => $request->license_plate,
                'zone_id' => auth()->user()?->zone_id,
                'status' => $request->status
            ]);

            return response()->json(['message' => 'Vehicle updated successfully!', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }


    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->back()->with('success', 'Vehicle deleted successfully!');
    }

    public function searchVehicle(Request $request)
    {
        $request->validate([
            'search' => 'required|string|max:40',
            'service_type' => 'required|in:self,external'
        ]);

        $vehicles = Vehicle::where('owner_type', $request->service_type =='self' ? '1' : '2')
                            ->where('license_plate', 'LIKE', "%{$request->search}%")
                            ->get();

        if ($vehicles->count() > 0) {
            return response()->json(['message' => 'Vehicles found!', 'type' => 'success', 'data' => $vehicles],200);
        } else {
            return response()->json(['message' => 'Vehicle not found!', 'type' => 'error'],200);
        }
    }


}
