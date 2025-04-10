<?php

namespace App\Http\Controllers;

use App\Actions\FetchRack;
use App\Models\Drawer;
use App\Models\Rack;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RackController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:rack-create')->only('store');
        $this->middleware('permission:rack-list')->only('index');
        $this->middleware('permission:rack-update')->only('edit', 'update');
        $this->middleware('permission:rack-delete')->only('destroy');
    }
    
    public function index(Request $request)
    {
        $racks = (new FetchRack)->execute($request);
        $zones = Zone::select('id', 'name')->get();

        if ($request->ajax()) {
            return view('components.racks.table', ['racks' => $racks])->render();
        }

        return view('backend.racks.index', compact('racks', 'zones'));
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:50|unique:racks,name',
            ]);
            // zone id comes from the auth
            $authId = Auth::id();
            $rack = Rack::create([
                'name' => $request->name,
                'zone_id' => $authId,
            ]);

            DB::commit();

            return response()->json(['message' => 'Rack created successfully!', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function update(Request $request, Rack $rack)
    {

        try {

            $request->validate([
                'name' => 'required|string|max:50|unique:racks,name,'.$rack->id,
            ]);

            $rack->update([
                'name' => $request->name,
                'zone_id' => Auth::id(),
            ]);

            return response()->json(['message' => 'Rack updated successfully', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }

    public function destroy(Rack $rack)
    {
        $drawerCount = Drawer::where('rack_id', $rack->id)->count();

        if ($drawerCount > 0) {
            return redirect()->back()->with('error', 'Rack has drawers, cannot delete!');
        }

        $rack->delete();

        return redirect()->back()->with('success', 'Rack deleted successfully!');
    }
}
