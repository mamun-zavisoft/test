<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceChart;
use App\Models\ServiceDetail;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    public function index() {
        $services = Service::get();
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
        // Validate the request
        $validator = Validator::make($request->all(), [
            'service_type' => 'required|in:self,external',
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_chart_ids' => 'required|array',
            'service_chart_ids.*' => 'exists:service_charts,id',
            'discount' => 'nullable|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'any_parts_purchase' => 'nullable|boolean',
            'parts' => 'required_if:any_parts_purchase,1|array',
            'parts.*.product_id' => 'required_if:any_parts_purchase,1|exists:products,id',
            'parts.*.quantity' => 'required_if:any_parts_purchase,1|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Check if parts are available in stock
        if ($request->has('any_parts_purchase') && $request->any_parts_purchase) {
            foreach ($request->parts as $part) {
                $product = Product::find($part['product_id']);
                $availableStock = $product->stock ?? 0;
                
                if ($part['quantity'] > $availableStock) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Insufficient stock',
                        'errors' => ['parts' => "Not enough {$product->name} in stock. Available: {$availableStock}"]
                    ], 422);
                }
            }
        }
        
        DB::beginTransaction();
        try {
            // Create the service
            $service = Service::create([
                'service_type' => $request->service_type,
                'vehicle_id' => $request->vehicle_id,
                'discount' => $request->discount ?? 0,
                'grand_total' => $request->grand_total,
                'total_amount' => $request->total_amount,
                'note' => $request->note,
                'payment_type_id' => $request->payment_type_id,
                'any_parts_purchase' => $request->has('any_parts_purchase'),
            ]);
            
            // Create service details
            foreach ($request->service_chart_ids as $chartId) {
                $serviceChart = ServiceChart::find($chartId);
                
                ServiceDetail::create([
                    'service_id' => $service->id,
                    'service_chart_id' => $chartId,
                    'price' => $serviceChart->price
                ]);
            }
            
            // Process parts if any
            if ($request->has('any_parts_purchase') && $request->any_parts_purchase) {
                foreach ($request->parts as $part) {
                    $product = Product::find($part['product_id']);
                    
                    // Reduce product stock
                    $product->decrement('stock', $part['quantity']);
                    
                    // Create stock history entry for parts used
                    ProductStock::create([
                        'product_id' => $part['product_id'],
                        'service_id' => $service->id,
                        'quantity' => -$part['quantity'], // Negative to indicate usage
                        'unit_price' => $product->sale_price,
                        'total_price' => $product->sale_price * $part['quantity']
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Service created successfully',
                'redirect' => route('admin.services.show', $service->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the service',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}