<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceChart;
use App\Actions\FetchServiceChart;
use Illuminate\Support\Facades\DB;

class ServiceChartController extends Controller
{
   
    public function index(Request $request)
    {
        $serviceCharts = (new FetchServiceChart)->execute($request);

        if ($request->ajax()) {
            return view('components.serviceCharts.table', ['entity' => $serviceCharts])->render();
        }

        return view('backend.serviceCharts.index', compact('serviceCharts'));

    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:50|unique:service_charts,name',
                'price' => 'required|numeric|min:1',
                'description' => 'nullable|string|max:4000',
                'code' => 'required|string|max:50|unique:service_charts,code'
            ]);

            DB::beginTransaction();

            $serviceChart = ServiceChart::create([
                'name' =>$request->name,
                'price' => $request->price,
                'description' => $request->description,
                'code' => $request->code
            ]);

            DB::commit();

            return response()->json(['message' => 'Service charts created successfully!', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }

    public function update(Request $request, ServiceChart $serviceChart)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:50|unique:service_charts,name,' . $serviceChart->id,
                'price' => 'required|numeric|min:1,' . $serviceChart->id,
                'description' => 'nullable|string|max:4000,' . $serviceChart->id,
                'code' => 'required|string|max:50|unique:service_charts,code,' . $serviceChart->id
            ]);

            $serviceChart->update([
                'name' =>$request->name,
                'price' => $request->price,
                'description' => $request->description,
                'code' => $request->code
            ]);

            return response()->json(['message' => 'Service chart updated successfully!', 'type' => 'success'],200);
        }catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }


    public function destroy(ServiceChart $serviceChart)
    {
        $serviceChart->delete();
        return redirect()->back()->with('success', 'Service chart deleted successfully!');
    }

}
