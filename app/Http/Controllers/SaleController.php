<?php

namespace App\Http\Controllers;

use App\Actions\FetchSale;
use App\Models\Account;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales = (new FetchSale)->execute($request);

        if ($request->ajax()) {
            return view('components.sales.table', ['sales' => $sales])->render();
        }

        return view('backend.sales.index', compact('sales'));
    }

    /**
     * Display the sales creation form
     */
    public function create()
    {
        $accounts = Account::select('id', 'name', 'balance')->get();
        $products = Product::select('id', 'name', 'purchase_price', 'sale_price', 'total_available_qty')->get();

        return view('backend.sales.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'parts' => 'required|array|min:1',
                'parts.*.product_id' => 'required|exists:products,id',
                'parts.*.rack_id' => 'required|exists:racks,id',
                'parts.*.drawer_id' => 'required|exists:drawers,id',
                'parts.*.quantity' => 'required|integer|min:1',
                'account_id' => 'required|exists:accounts,id',
                'discount' => 'nullable|numeric|min:0',
                'grand_total' => 'required|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
                'phone' => 'nullable|regex:/^01[3-9]\d{8}$/',
                'note' => 'nullable|string|max:4000',
            ]);

            DB::beginTransaction();

            // Create sale record
            $sale = Sale::create([
                'transaction_id' => PurchaseController::transactionIdGenerate(),
                'account_id' => $request->account_id,
                'type' => 'only_sale',
                'grand_total' => $request->grand_total,
                'discount_amount' => $request->discount ?? 0,
                'paid_amount' => $request->grand_total,
                'due_amount' => 0,
                'paid_status' => 'full_paid',
                'account_id' => $request->account_id,
                'note' => $request->note,
                'phone' => $request->phone,
            ]);

            // Process parts sale
            foreach ($request->parts as $part) {
                // Validate part availability
                $isAvailable = $this->validatePartAvailability(
                    $part['product_id'],
                    $part['rack_id'],
                    $part['drawer_id'],
                    $part['quantity']
                );

                if (! $isAvailable['is_available']) {
                    throw new \Exception("Product ID {$part['product_id']} is not available in the requested quantity");
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

                // Update stock histories
                foreach ($availableHistories as $history) {
                    DB::table('stock_histories')
                        ->where('id', $history->id)
                        ->update(['sale_id' => $sale->id]);
                }

                // Decrement product total available quantity
                DB::table('products')
                    ->where('id', $part['product_id'])
                    ->decrement('total_available_qty', $part['quantity']);

                // Create sale details
                $sale->saleDetails()->create([
                    'product_id' => $part['product_id'],
                    'unit_price' => $part['price'],
                    'qty' => $part['quantity'],
                ]);
            }

            // Update account balance
            $account = Account::findOrFail($request->account_id);
            $account->balance += $request->grand_total;
            $account->save();

            $payment = $sale->payment()->create([
                'transaction_type' => 'sale',
                'grand_total' => $request->grand_total,
                'due_amount' => 0,
                'paid_amount' => $request->grand_total,
                'paid_status' => 'full_paid',
            ]);
            if ($payment) {
                $payment->paymentDetails()->create([
                    'account_id' => $request->account_id,
                    'amount' => $request->grand_total,
                    'date' => date('Y-m-d'),
                    'note' => $request->note,
                ]);
            }

            DB::commit();

            return response()->json([
                'type' => 'success',
                'message' => 'sale completed successfully',
                'redirectUrl' => route('admin.sales.index'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    // Validate part availability in specific location
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
            'available_qty' => $availableQty,
        ];
    }
}
