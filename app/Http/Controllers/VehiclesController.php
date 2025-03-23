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
                'vehicle_type' => 'required|in:1,2,3,4,5',
                'hub_id' => 'required|exists:hubs,id',
                'vehicle_model_id' => 'required|exists:vehicle_models,id',
                'registration_date' => 'required|date',
                'registration_validity' => 'required|date',
                'tax_token_validity' => 'required|date',
                'fitness_validity' => 'required|date',
                'road_permit_validity' => 'required|date',
                'insurance_validity' => 'required|date',
                'license_plate' => 'required|string|max:50|unique:vehicles,license_plate',
                'status' => 'required|in:1,2'
            ]);

            DB::beginTransaction();

            $vehicle = Vehicle::create([
                'owner_type' => $request->owner_type,
                'vehicle_type' => $request->vehicle_type,
                'hub_id' => $request->hub_id,
                'vehicle_model_id' => $request->vehicle_model_id,
                'registration_date' => $request->registration_date,
                'registration_validity' => $request->registration_validity,
                'tax_token_validity' => $request->tax_token_validity,
                'fitness_validity' => $request->fitness_validity,
                'road_permit_validity' => $request->road_permit_validity,
                'insurance_validity' => $request->insurance_validity,
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
                'vehicle_type' => 'required|in:1,2,3,4,5,' . $vehicle->id,
                'hub_id' => 'required|exists:hubs,id',
                'vehicle_model_id' => 'required|exists:vehicle_models,id',
                'registration_date' => 'required|date,' . $vehicle->id,
                'registration_validity' => 'required|date,' . $vehicle->id,
                'tax_token_validity' => 'required|date.' . $vehicle->id,
                'fitness_validity' => 'required|date,' . $vehicle->id,
                'road_permit_validity' => 'required|date,' . $vehicle->id,
                'insurance_validity' => 'required|date,' . $vehicle->id,
                'license_plate' => 'required|string|max:50|unique:vehicles,license_plate,' . $vehicle->id,
                'status' => 'required|in:1,2'
            ]);


            $vehicle->update([
                'owner_type' => $request->owner_type,
                'vehicle_type' => $request->vehicle_type,
                'hub_id' => $request->hub_id,
                'vehicle_model_id' => $request->vehicle_model_id,
                'registration_date' => $request->registration_date,
                'registration_validity' => $request->registration_validity,
                'tax_token_validity' => $request->tax_token_validity,
                'fitness_validity' => $request->fitness_validity,
                'road_permit_validity' => $request->road_permit_validity,
                'insurance_validity' => $request->insurance_validity,
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
