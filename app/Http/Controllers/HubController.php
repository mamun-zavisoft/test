<?php

namespace App\Http\Controllers;

use App\Actions\FetchHub;
use App\Models\Hub;
use App\Models\VehicleModel;
use App\Models\Zone;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HubController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hub-create')->only('create', 'store');
        $this->middleware('permission:hub-list')->only('index');
        $this->middleware('permission:hub-update')->only('edit', 'update');
        $this->middleware('permission:hub-delete')->only('destroy');
    }
    
    public function index(Request $request)
    {

        $hubs = (new FetchHub)->execute($request);
        $zones = Zone::select('id', 'name')->get();

        if ($request->ajax()) {
            return view('components.hubs.table', ['hubs' => $hubs, 'zones' => $zones])->render();
        }

        return view('backend.hubs.index', ['title' => 'Hubs'], compact('hubs', 'zones'));
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:50|unique:hubs,name',
                'zone_id' => 'nullable|exists:zones,id',
                'custom_hub_id' => 'required|string|unique:hubs,custom_hub_id',
                'phone' => 'nullable|string|max:15|unique:hubs,phone',
                'address' => 'nullable|string|max:255|unique:hubs,address',
            ],[
                'custom_hub_id.unique' => 'Hub id already exists',
            ]);

            DB::beginTransaction();

            $hub = Hub::create([
                'name' => $request->name,
                'zone_id' => auth()->user()?->zone_id,
                'custom_hub_id' => $request->custom_hub_id,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            DB::commit();

            return response()->json(['message' => 'Hub created successfully', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function show ($id) {

        try {
            $hub = Hub::with('vehicles.vehicleModel', 'vehicles.hub')->findOrFail($id);
            $vehicles = $hub->vehicles()->paginate(request('per_page', 10))->withQueryString();
            $all_hubs = Hub::select('id', 'name')->get();
            $vehicleModels = VehicleModel::select('id', 'name')->get();

            return view('backend.hubs.show', get_defined_vars());
        } catch (ModelNotFoundException) {
            return redirect()->back()->with('error', 'Hub not found');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, Hub $hub)
    {

        try {

            $request->validate([
                'name' => 'required|string|max:50',
                'zone_id' => 'nullable|exists:zones,id',
                'custom_hub_id' => 'required|string|unique:hubs,custom_hub_id,'.$hub->id,
                'phone' => 'nullable|string|max:15|unique:hubs,phone,'.$hub->id,
                'address' => 'nullable|string|max:255|unique:hubs,address,'.$hub->id,
            ]);

            DB::beginTransaction();

            $hub->update([
                'name' => $request->name,
                'zone_id' => auth()->user()?->zone_id,
                'custom_hub_id' => $request->custom_hub_id,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            DB::commit();

            return response()->json(['message' => 'Hub updated successfully', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function destroy(Hub $hub)
    {
        if ($hub->vehicles()->exists()) 
        {
            return redirect()->back()->with('error', 'Hub has vehicles, cannot delete!');
        }
        $hub->delete();

        return redirect()->back()->with('success', 'Hub deleted successfully!');
    }
}
