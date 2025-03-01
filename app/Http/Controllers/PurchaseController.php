<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
                'redirectUrl' =>  route('admin.purchases.index'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function statusChange(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,received',
        ]);
        try {
            $purchase = Purchase::findOrFail($id);
            $purchase->update(['status' => $request->status]);

            return redirect()->route('admin.purchases.index')->with('success', 'Purchase status updated successfully.');
        } catch (ModelNotFoundException) {
            return redirect()->route('admin.purchases.index')->with('error', 'Purchase not found.');
        } catch (\Exception $e) {
            return redirect()->route('admin.purchases.index')->with('error', $e->getMessage());
        }
    }
}
