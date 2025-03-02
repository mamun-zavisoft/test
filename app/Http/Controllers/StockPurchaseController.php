<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Rack;
use App\Models\StockHistory;
use App\Models\StockPurchase;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockPurchaseController extends Controller
{
    public function create($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);

            if ($purchase->status == 'stored') {
               return redirect()->back()->with('error', 'Purchase is already stored');
            }

            $products = $purchase->products;
            $racks = Rack::with('drawers')->select('id', 'name')->get();
            return view('backend.stock_purchases.create', compact('purchase', 'products', 'racks'));
        } catch (ModelNotFoundException) {
            return back()->with('error', 'Purchase not found.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request, $id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return response()->json([
                'type' => 'error',
                'message' => 'Purchase not found'
            ]);
        }
        if ($purchase->status == 'stored') {
            return response()->json([
                'type' => 'error',
                'message' => 'Purchase is already stored'
            ]);
        }
        
        if ($purchase->status != 'received') {
            return response()->json([
                'type' => 'error',
                'message' => 'Purchase is not received yet'
            ]);
        }
        // dd($request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.locations' => 'required|array',
            'products.*.locations.*.product_id' => 'required|exists:products,id',
            'products.*.locations.*.rack_id' => 'required|exists:racks,id',
            'products.*.locations.*.drawer_id' => 'required|exists:drawers,id',
            'products.*.locations.*.quantity' => 'required|integer|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Verify total quantity for each product doesn't exceed purchased quantity
        foreach ($request->products as $productId => $productData) {
            $totalAssigned = collect($productData['locations'])->sum('quantity');
            
            // dd($purchase);    
            $purchasedQty = $purchase->purchaseDetails()
                ->where('product_id', $productId)
                ->value('quantity');
            if ($totalAssigned > $purchasedQty) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Validation failed',
                    'errors' => ["products.$productId.quantity" => "Total assigned quantity ($totalAssigned) exceeds the purchased quantity ($purchasedQty)"]
                ], 422);
            }
        }
    
        DB::beginTransaction();
        try {
            // Get purchase details for price information
            $purchaseDetails = $purchase->purchaseDetails()->get()
                ->keyBy('product_id');
    
            // Process each product
            foreach ($request->products as $productId => $productData) {
                // Get purchase and sale prices from purchase details
                $purchaseDetail = $purchaseDetails[$productId];
                
                // Process each storage location for this product
                foreach ($productData['locations'] as $location) {
                    // Create stock purchase record
                    $product = Product::find($location['product_id']);
                    $stockPurchase = new StockPurchase([
                        'product_id' => $location['product_id'],
                        'purchase_id' => $purchase->id,
                        'rack_id' => $location['rack_id'],
                        'drawer_id' => $location['drawer_id'],
                        'purchase_price' => $purchaseDetail->price,
                        'sale_price' => $purchaseDetail->sale_price ?? $product->sale_price,
                        'qty' => $location['quantity']
                    ]);
                    $product->total_available_qty += $location['quantity'];
                    $product->save();
                    
                    $stockPurchase->save();
                    
                    // Create stock history records (one for each unit)
                    for ($j = 0; $j < $location['quantity']; $j++) {
                        StockHistory::create([
                            'stock_purchase_id' => $stockPurchase->id,
                            'uuid' => \Str::uuid(),
                            'sale_id' => null
                        ]);
                    }
                }
            }

            $purchase->status = 'stored';
            $purchase->save();
            
            DB::commit();
            return response()->json([
                'type' => 'success',
                'message' => 'Products stored in racks successfully.',
                'redirectUrl' => route('admin.purchases.index'),
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'type' => 'error',
                'message' => 'An error occurred while storing products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
