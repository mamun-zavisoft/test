<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Service;
use App\Models\ServiceChart;
use App\Models\ServiceDetail;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    public function index()
    {
        $services = Service::with('vehicle')->get();
        return view('backend.services.index', compact('services'));
    }
    /**
     * Display the service creation form
     */
    public function create()
    {
        $vehicles = Vehicle::get();
        $serviceCharts = ServiceChart::all();
        $products = Product::get();

        return view('backend.services.create', compact('vehicles', 'serviceCharts', 'products'));
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        // Begin transaction to ensure data integrity
        DB::beginTransaction();

        try {
            // Validate the request data
            $validated = $request->validate([
                'service_type' => 'required|in:self,external',
                'vehicle_id' => 'required|exists:vehicles,id',
                'service_chart_ids' => 'required|array|exists:service_charts,id',
                'any_parts_purchase' => 'required|boolean',
                'parts' => 'required_if:any_parts_purchase,1|array',
                'payment_type_id' => 'nullable|integer',
                'discount' => 'nullable|numeric',
                'note' => 'nullable|string',
                'grand_total' => 'required|numeric',
                'total_amount' => 'required|numeric',
            ]);

            // Create service record
            $service = Service::create([
                'service_type' => $request->service_type,
                'vehicle_id' => $request->vehicle_id,
                'payment_type_id' => $request->payment_type_id,
                'discount' => $request->discount ?? 0,
                'note' => $request->note,
                'grand_total' => $request->grand_total,
                'total_amount' => $request->total_amount,
                'any_parts_purchase' => $request->any_parts_purchase
            ]);

            // Associate service charts
            foreach ($request->service_chart_ids as $chartId) {
                $chart = ServiceChart::select('id', 'price')->where('id', $chartId)->first();
                ServiceDetail::create([
                    'service_id' => $service->id,
                    'service_chart_id' => $chartId,
                    'price' => $chart->price, 
                ]);
            }

            // Process parts if any
            if ($request->any_parts_purchase && !empty($request->parts)) {

                $sale = Sale::create([
                    'type' => $request->service_type,
                    'due_amount' => $request->total_amount, 
                    'pay_amount' => 0, // Initially no payment
                    'total_amount' => $request->total_amount,
                    'payment_status' => Sale::$FULL_DUE,
                    'note' => $request->note,
                ]);

                $service->update(['sale_id' => $sale->id]);

                foreach ($request->parts as $part) {
                    // Validate part availability
                    $isAvailable = $this->validatePartAvailability(
                        $part['product_id'],
                        $part['rack_id'],
                        $part['drawer_id'],
                        $part['quantity']
                    );

                    if (!$isAvailable['is_available']) {
                        throw new \Exception("Product ID {$part['product_id']} is not available in the requested quantity in Drawer {$part['drawer_id']}");
                    }

                    // Get the specific stock_purchase records for this drawer and product
                    $stockPurchases = DB::table('stock_purchases')
                        ->where('product_id', $part['product_id'])
                        ->where('drawer_id', $part['drawer_id'])
                        ->get();

                    $stockPurchaseIds = $stockPurchases->pluck('id')->toArray();

                    // Get available stock_histories entries for these stock_purchases
                    $availableHistories = DB::table('stock_histories')
                        ->whereIn('stock_purchase_id', $stockPurchaseIds)
                        ->whereNull('sale_id')
                        ->limit($part['quantity'])
                        ->get();

                    foreach ($availableHistories as $history) {
                        DB::table('stock_histories')
                            ->where('id', $history->id)
                            ->update(['sale_id' => $sale->id]); 
                    }

                    $sale->SaleDetails()->create([
                        'product_id' => $part['product_id'],
                        'unit_price' => $part['unit_sale_price'],
                        'qty' => $part['quantity'],
                    ]);
                }

            }

            DB::commit();
            return response()->json([
                'type' => 'success',
                'message' => 'Service created successfully',
                'redirectUrl' => route('admin.services.index')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Validate if a part is available in the requested quantity
     */
    private function validatePartAvailability($productId, $rackId, $drawerId, $requestedQty)
    {
        // Get stock purchases for this product in the selected drawer
        $stockPurchases = DB::table('stock_purchases')
            ->where('product_id', $productId)
            ->where('drawer_id', $drawerId)
            ->get();

        if ($stockPurchases->isEmpty()) {
            return ['is_available' => false];
        }

        // Collect all stock purchase IDs
        $stockPurchaseIds = $stockPurchases->pluck('id')->toArray();

        // Count available quantity across all purchases
        $availableQty = DB::table('stock_histories')
            ->whereIn('stock_purchase_id', $stockPurchaseIds)
            ->whereNull('sale_id')
            ->count();

        return [
            'is_available' => $availableQty >= $requestedQty,
            'available_qty' => $availableQty
        ];
    }
}
