<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\PurchaseService;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
    
    public function index()
    {
        $perPage = $request->per_page ?? 10;
        $purchases = $this->purchaseService->getAllPurchases($perPage);
      
        return view('backend.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::select('id', 'name')->get();
        return view('backend.purchases.create', compact('suppliers'));
    }

    public function store(PurchaseRequest $request)
    {
        try {
            $this->purchaseService->createPurchase($request->validated());
            return response()->json([
                'message' => 'Purchase created successfully.',
                'type' => 'success',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Calculate the paid status based on grand total and paid amount.
     *
     * @param float $grandTotal
     * @param float $paidAmount
     * @return string
     */
    private function calculatePaidStatus($grandTotal, $paidAmount)
    {
        if ($paidAmount == 0) {
            return 'full_due';
        } elseif ($paidAmount < $grandTotal) {
            return 'partial_paid';
        } else {
            return 'full_paid';
        }
    }
}
