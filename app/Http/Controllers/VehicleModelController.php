<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\DB;

class VehicleModelController extends Controller
{
    public function index(){
        $perPage = request()->input('per_page', 10);
        $vehicleModels = VehicleModel::all()->paginate($perPage);
        
        return view('backend.vehicle_models.index', compact('vehicleModels'));
    }


    public function store(Request $request){
      try{
        $request->validate([
            'name' => 'required|string|max:50',
            'manufacturer' => 'nullable|string|max:50',
            'engine_cc' => 'nullable|integer',
            'fuel_capacity' => 'nullable|numeric',
            'payload_capacity' => 'nullable|numeric',
            'body_length' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        $vehicleModel = VehicleModel::create([
            'name' => $request->name,
            'manufacturer' => $request->manufacturer,
            'engine_cc' => $request->engine_cc,
            'fuel_capacity' => $request->fuel_capacity,
            'payload_capacity' => $request->payload_capacity,
            'body_length' => $request->body_length,
        ]);

            DB::commit();
            return response()->json(['message' => 'Vehicle Model created successfully', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'type' => 'error'],500);
            
        } 
    }

    public function update(Request $request, VehicleModel $vehicleModel){
        try{
            $data = $request->validate([
                'name' => 'required|string|max:50',
                'manufacturer' => 'nullable|string|max:50',
                'engine_cc' => 'nullable|integer',
                'fuel_capacity' => 'nullable|numeric',
                'payload_capacity' => 'nullable|numeric',
                'body_length' => 'nullable|numeric',
            ]);

            DB::beginTransaction();

            $vehicleModel->update($data);

            DB::commit();
            return response()->json(['message' => 'Vehicle Model updated successfully', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'type' => 'error'],500);
            
        }
    }

    public function destroy(VehicleModel $vehicleModel){
        $vehicleModel->delete();
        return response()->json(['message' => 'Vehicle Model deleted successfully', 'type' => 'success'],200);
    }
    
}
