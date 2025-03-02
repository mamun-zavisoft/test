<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Account;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $accounts = Account::select('id', 'name', 'balance')->get();

        return view('backend.purchases.index', compact('purchases', 'accounts'));
    }

    public function create()
    {
        $accounts = Account::select('id', 'name', 'balance')->get();
        $suppliers = Supplier::select('id', 'name')->get();
        return view('backend.purchases.create', compact('suppliers', 'accounts'));
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

    public function payment(Request $request, $id)
    {
        $request->validate([
            'payment_type' => 'required|in:partial_paid,full_paid',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'nullable|numeric|min:1',
            'payment_date' => 'nullable|date|before_or_equal:today',
            'note' => 'nullable|string',
        ]);

        $purchase = Purchase::findOrFail($id);
        $account = Account::findOrFail($request->account_id);
        try {
            if ($request->amount > $account->balance) {
                return response()->json(['message' => 'Payment amount cannot be greater than account balance ' . $account->balance, 'type' => 'error'], 422);
            }
            if ($request->payment_type == 'partial_paid' && $request->amount > $purchase->due_amount) {
                return response()->json(['message' => 'Payment amount cannot be greater than due amount ' . $purchase->due_amount, 'type' => 'error'], 422);
            }

            $latest_due_amount = $purchase->due_amount - $request->amount;
            $total_paid_amount = $purchase->paid_amount + $request->amount;

            $paid_status = $total_paid_amount < $purchase->grand_total ? 'partial_paid' : 'full_paid';

            DB::beginTransaction();
            $purchase->update([
                'due_amount' => $latest_due_amount,
                'paid_amount' => $total_paid_amount,
                'paid_status' => $paid_status,
            ]);
            
            $account->balance = $account->balance - $request->amount;
            $account->save();

            $payment = $purchase->payment;
            if ($payment) {
                $payment->update([
                    'due_amount' => $latest_due_amount,
                    'paid_amount' => $total_paid_amount,
                    'paid_status' => $paid_status,
                ]);

                $payment->paymentDetails()->create([
                    'account_id' => $request->account_id,
                    'amount' => $request->amount,
                    'date' => $request->payment_date,
                    'note' => $request->note,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Payment created successfully.',
                'type' => 'success',
                'redirectUrl' =>  route('admin.purchases.index'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
