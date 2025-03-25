<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hub;
use App\Actions\FetchHub;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class HubController extends Controller
{
    public function index(Request $request){

        $hubs = (new FetchHub())->execute($request);
        $zones = Zone::select('id', 'name')->get();

        if ($request->ajax()) {
            return view('components.hubs.table', ['hubs' => $hubs, 'zones' => $zones])->render();
        }

        return view('backend.hubs.index', compact('hubs', 'zones'));
    }

    public function store(Request $request){

        try{
            $request->validate([
                'name' => 'required|string|max:50|unique:hubs,name',
                'zone_id' => 'nullable|exists:zones,id',
                'custom_hub_id' => 'required|string|unique:hubs,custom_hub_id',
                'phone' => 'nullable|string|max:15|unique:hubs,phone',
                'address' => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            $hub = Hub::create([
                'name' => $request->name,
                'zone_id' => auth()->user()?->zone_id,
                'custom_hub_id' => $request->custom_hub_id,
                'phone' => $request->phone,
                'address' => $request->address
            ]);

            DB::commit();

            return response()->json(['message' => 'Hub created successfully', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function update(Request $request, Hub $hub){

        try{

            $request->validate([
                'name' => 'required|string|max:50',
                'zone_id' => 'nullable|exists:zones,id',
                'custom_hub_id' => 'required|string|unique:hubs,custom_hub_id,' . $hub->id,
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            $hub->update([
                'name' => $request->name,
                'zone_id' => auth()->user()?->zone_id,
                'custom_hub_id' => $request->custom_hub_id,
                'phone' => $request->phone,
                'address' => $request->address
            ]);

            DB::commit();

            return response()->json(['message' => 'Hub updated successfully', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function destroy(Hub $hub){
        
        $hub->delete();
        return redirect()->back()->with('success', 'Hub deleted successfully!');
    }
    
}
