<?php

namespace App\Http\Controllers;

use App\Actions\FetchDrawer;
use App\Models\Drawer;
use App\Models\Rack;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Node\FunctionNode;

class DrawerController extends Controller
{

    public function index(Request $request)
    {
        $drawers = (new FetchDrawer)->execute($request);
        $racks = Rack::select('id', 'name')->get();
       
        return view('backend.drawers.index', compact('drawers', 'racks'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'rack_id' => 'required|integer|exists:racks,id',
                'name' => 'required|string|max:50',
            ]);

            $existingDrawer = Drawer::where('rack_id', $data['rack_id'])->where('name', $data['name'])->first();

            if ($existingDrawer) {
                return response()->json(['message' => 'The drawer name already exists for this rack.', 'type' => 'error'], 422);
            }

            Drawer::create($data);

            return response()->json(['message' => 'Drawer created successfully!', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }


    public function update(Request $request, Drawer $drawer)
    {
        try {
            $data = $request->validate([
                'rack_id' => 'required|integer|exists:racks,id',
                'name' => 'required|string|max:50',
            ]);

            if (Drawer::where('rack_id', $data['rack_id'])
                ->where('name', $data['name'])
                ->where('id', '!=', $drawer->id)
                ->exists()) {
                return response()->json(['message' => 'The drawer name already exists for this rack.', 'type' => 'error'], 422);
            }

            $drawer->update($data);
            
            return response()->json(['message' => 'Drawer updated successfully', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }


    public function destroy(Drawer $drawer)
    {
        $drawerCount = $drawer->productCount($drawer->id);

        if ($drawerCount > 0) {
            return redirect()->back()->with('error', 'Drawer has ' . $drawerCount . ' products, cannot delete!');
        }

        $drawer->delete();
        return redirect()->back()->with('success', 'Drawer deleted successfully!');
    }

    public function fetchDrawersByRack($rackId) {
        try {
            $rack = Rack::findOrFail($rackId);

            return response()->json(['data' => $rack->drawers, 'type' => 'success'], 200);
        } catch(ModelNotFoundException) {
            return response()->json(['message' => 'Rack not found', 'type' => 'error'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'type' => 'error'], 500);
        }
    }
}
