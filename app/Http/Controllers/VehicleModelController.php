<?php

namespace App\Http\Controllers;

use App\Actions\FetchVehicleModel;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:vehicle-model-create')->only('create', 'store');
        $this->middleware('permission:vehicle-model-list')->only('index');
        $this->middleware('permission:vehicle-model-update')->only('edit', 'update');
        $this->middleware('permission:vehicle-model-delete')->only('destroy');
    }
    
    public function index(Request $request)
    {

        $vehicleModels = (new FetchVehicleModel)->execute($request);

        if ($request->ajax()) {
            return view('components.vehicleModels.table', ['vehicleModels' => $vehicleModels])->render();
        }

        return view('backend.vehicleModels.index', ['title' => 'Vehicle Models'], compact('vehicleModels'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50|unique:vehicle_models,name',
                'manufacturer' => 'required|string|max:50',
                'engine_cc' => 'required|numeric',
                'fuel_capacity' => 'required|numeric',
                'payload_capacity' => 'required|numeric',
                'body_length' => 'nullable|numeric',
                'avg_mileage' => 'nullable|numeric',
            ]);

            DB::beginTransaction();

            $vehicleModel = VehicleModel::create([
                'name' => $request->name,
                'manufacturer' => $request->manufacturer,
                'engine_cc' => $request->engine_cc,
                'fuel_capacity' => $request->fuel_capacity,
                'payload_capacity' => $request->payload_capacity,
                'body_length' => $request->body_length,
                'avg_mileage' => $request->avg_mileage ?? 0,
            ]);

            DB::commit();

            return response()->json(['message' => 'Vehicle Model created successfully', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage(), 'type' => 'error'], 500);

        }
    }

    public function update(Request $request, VehicleModel $vehicleModel)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:50',
                'manufacturer' => 'required|string|max:50',
                'engine_cc' => 'required|numeric',
                'fuel_capacity' => 'required|numeric',
                'payload_capacity' => 'required|numeric',
                'body_length' => 'nullable|numeric',
                'avg_mileage' => 'nullable|numeric',
            ]);

            DB::beginTransaction();

            $vehicleModel->update($data);

            DB::commit();

            return response()->json(['message' => 'Vehicle Model updated successfully', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage(), 'type' => 'error'], 500);

        }
    }

    public function destroy(VehicleModel $vehicleModel)
    {
        if ($vehicleModel->vehicles()->exists()) {
            return redirect()->back()->with('error', 'Vehicle Model cannot be deleted as it is associated with vehicles.');
        }
        $vehicleModel->delete();

        return redirect()->back()->with('success', 'Vehicle Model deleted successfully!');
    }
}
