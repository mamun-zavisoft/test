<?php

namespace App\Http\Controllers;

use App\Media\Media;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Supplier;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get all vehicle counts in a single query
        $vehicleCounts = Vehicle::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN owner_type = 1 THEN 1 ELSE 0 END) as self_count,
            SUM(CASE WHEN owner_type = 2 THEN 1 ELSE 0 END) as outside_count
        ')->first();

        // Get total supplier and service counts
        $totalSupplier = Supplier::count();
        $totalService = Service::count();

        // Get purchase data with one query
        $purchaseTotals = Purchase::selectRaw('
            SUM(due_amount) as total_due_amount,
            SUM(grand_total) as total_purchase_amount,
            SUM(paid_amount) as total_purchase_paid_amount
        ')->first();

        // Get the last 5 purchases with proper eager loading
        $purchases = Purchase::with(['purchaseDetails.product' => function ($query) {
            $query->select('id', 'name', 'purchase_price');
        }, 'purchaseDetails.product.media'])
            ->select(
                'id',
                'zone_id',
                'supplier_id',
                'discount_amount',
                'shipping_charge',
                'paid_amount',
                'due_amount',
                'paid_status',
                'reference_no',
                'status',
                'transaction_id',
                'grand_total'
            )
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Eager load service details with proper relations
        $services = Service::with([
            'vehicle:id,license_plate,owner_type',
            'serviceDetails.serviceChart:id,name,price,code',
            'sale.saleDetails.product:id,name',
        ])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Get sales totals with one query - adding count for the view
        $sales = Sale::selectRaw('COUNT(*) as count, SUM(grand_total) as grand_total, SUM(due_amount) as due_amount')
            ->first();

        // Filter by year and get chart data
        $year = $request->input('year', date('Y'));
        $chartData = $this->getSalePurchaseData($year);
        // dd($chartData);

        return view('index', [
            'totalVehicle' => $vehicleCounts->total,
            'selfVehicle' => $vehicleCounts->self_count,
            'outsideVehicle' => $vehicleCounts->outside_count,
            'totalSupplier' => $totalSupplier,
            'totalService' => $totalService,
            'totalDueAmount' => $purchaseTotals->total_due_amount,
            'totalPurchaseAmount' => $purchaseTotals->total_purchase_amount,
            'totalPurchasePaidAmount' => $purchaseTotals->total_purchase_paid_amount,
            'sales' => $sales,
            'chartData' => $chartData,
            'year' => $year,
            'purchases' => $purchases,
            'services' => $services,
            // No need to pass serviceCharts separately since they're already eager loaded
        ]);
    }

    private function getSalePurchaseData($year)
    {

        // Get all monthly sales in a single query
        $salesByMonth = Sale::selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Get all monthly purchases in a single query
        $purchasesByMonth = Purchase::selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthName = date('M', mktime(0, 0, 0, $month, 1, $year));

            $chartData[] = [
                'month' => $monthName,
                'sales' => (float) ($salesByMonth[$month] ?? 0),
                'purchases' => (float) ($purchasesByMonth[$month] ?? 0),
            ];
        }

        return $chartData;
    }

    public function deleteMedia(Request $request)
    {
        $request->validate([
            'model'             => 'required',
            'model_id'          => 'required',
            'media_id'          => 'nullable|exists:media,id',
            'collection_name'   => 'nullable|exists:media,collection_name',
        ]);

        try {

            $model = "App\Models\\{$request->model}"::findOrFail($request->model_id);

            if ($request->collection_name) {
                $existingMedia =  $model->media()->where('collection_name', $request->collection_name)->first();
            }

            if ($request->media_id) {
                $existingMedia =  $model->media()->where('id', $request->media_id)->first();
            }

            if ($existingMedia) {
                $model->deleteMedia($existingMedia->id);
            }

            return response()->json(['type' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 422);
        }
    }
}
