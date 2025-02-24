<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\Zone;
use App\Actions\FetchRack;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RackController extends Controller
{
   
    public function index(Request $request)
    {
        $racks = (new FetchRack)->execute($request);
        $zones = Zone::select('id', 'name')->get();

        if ($request->ajax()) {
            return view('components.racks.table', ['entity' => $racks])->render();
        }

        return view('backend.racks.index', compact('racks', 'zones'));
    }

    public function store(Request $request)
    {
        try{

            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:50'
            ]);
            // zone id comes from the auth
            // $authId =  Auth::id();
            $rack = Rack::create([
                'name' => $request->name,
                'zone_id' => 1
            ]);

            DB::commit();
            return response()->json(['message' => 'Rack created successfully!', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }


    public function update(Request $request, Rack $rack)
    {
        
        try{

            $request->validate([
                'name' => 'required|string|max:50' . $rack->id,
            ]);
            
            $rack->update([
                'name' => $request->name,
                'zone_id' => 1
            ]);


            return response()->json(['message' => 'Rack updated successfully', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }


    public function destroy(Rack $rack)
    {

        $rack->delete();
        return redirect()->back()->with('success', 'Rack deleted successfully!');
    }
}
