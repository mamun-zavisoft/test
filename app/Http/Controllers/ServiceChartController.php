<?php

namespace App\Http\Controllers;

use App\Actions\FetchServiceChart;
use App\Models\ServiceChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceChartController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:service-chart-create')->only(['create', 'store']);
        $this->middleware('permission:service-chart-list')->only(['index']);
        $this->middleware('permission:service-chart-update')->only(['edit', 'update']);
        $this->middleware('permission:service-chart-delete')->only(['destroy']);
    }
    
    public function index(Request $request)
    {
        $serviceCharts = (new FetchServiceChart)->execute($request);

        if ($request->ajax()) {
            return view('components.serviceCharts.table', ['serviceCharts' => $serviceCharts])->render();
        }

        return view('backend.serviceCharts.index', compact('serviceCharts'));

    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50|unique:service_charts,name',
                'price' => 'required|numeric|min:1|max:10000000',
                'description' => 'nullable|string|max:4000',
                'code' => 'nullable|string|max:50|unique:service_charts,code'
            ],[
                'price.max' => 'The price must not be greater than 10,000,000'
            ]);

            DB::beginTransaction();

            $serviceChart = ServiceChart::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'code' => $request->code,
            ]);

            DB::commit();

            return response()->json(['message' => 'Service charts created successfully!', 'type' => 'success', 'serviceChart' => $serviceChart], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }

    public function update(Request $request, ServiceChart $serviceChart)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50|unique:service_charts,name,' . $serviceChart->id,
                'price' => 'required|numeric|max:10000000|min:1,' . $serviceChart->id,
                'description' => 'nullable|string|max:4000,' . $serviceChart->id,
                'code' => 'nullable|string|max:50|unique:service_charts,code,' . $serviceChart->id
            ], [
                'price.max' => 'The price must not be greater than 10,000,000'
            ]);

            $serviceChart->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'code' => $request->code,
            ]);

            return response()->json(['message' => 'Service chart updated successfully!', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'type' => 'error']);
        }

    }

    public function destroy(ServiceChart $serviceChart)
    {
        $serviceChart->delete();

        return redirect()->back()->with('success', 'Service chart deleted successfully!');
    }
}
