<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\FetchZone;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    
    public function index(Request $request) 
    {
        $zones = (new FetchZone)->excute($request);

        if ($request->ajax()) {
            return view('component.zones.table', ['entity' => $zones])->render();
        }

        return view('backend.zones.index',compact('zones'));
        
    }


    public function store(Request $request)
    { 
        try{

            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:50|unique:zones,name',
                'location' => 'required|string|max:255',
                'phone' => 'required|regex:/^01[3-9]\d{8}$/|unique:zones,phone',
            ]);
    
            $zone = Zone::create([
                'name' => $request->name,
                'location' => $request->location,
                'phone' => $request->phone
            ]);

            DB::commit();

            return response()->json(['message' => 'Zone created successfully!', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }


    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:zones,name,' . $zone->id,
            'location' => 'required|string|max:255,' . $zone->id,
            'phone' => 'required|regex:/^01[3-9]\d{8}$/|unique:zones,phone,' . $zone->id,
        ]);

        try{

            $zone->update([
                'name' => $request->name,
                'location' => $request->location,
                'phone' => $request->phone,
            ]);

            return response()->json(['message' => 'Zone updated successfully!', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }


    public function destroy(Zone $zone)
    {
        $zone->delete();
        return redirect()->back()->with('success', 'Zone deleted successfully!');
    }

    


}
