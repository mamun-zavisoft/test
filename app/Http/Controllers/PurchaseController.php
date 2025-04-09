<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Account;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Zone;
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
        $suppliers = Supplier::select('id', 'name')->get();

        if (request()->ajax()) {
            return view('components.purchases.table', ['purchases' => $purchases])->render();
        }

        return view('backend.purchases.index', compact('purchases', 'accounts', 'suppliers'));
    }

    public function create()
    {
        $accounts = Account::select('id', 'name', 'balance')->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $zones = Zone::select('id', 'name')->get();

        return view('backend.purchases.create', compact('suppliers', 'accounts', 'zones'));
    }

    public function store(PurchaseRequest $request)
    {
        try {
            DB::beginTransaction();

            $purchaseData = $request->validated();
            $purchaseData['paid_amount'] = 0;
            $purchaseData['payment_type'] = 'full_due';

            $purchase = $this->purchaseService->createPurchase($purchaseData);

            if ($request->payment_type != 'full_due') {
                $this->payment($request, $purchase);
            }

            DB::commit();

            return response()->json([
                'message' => 'Purchase created successfully.',
                'type' => 'success',
                'redirectUrl' => route('admin.purchases.index'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

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

    public function payment(Request $request, $purchase)
    {
        if (! $purchase instanceof Purchase) {
            $request->validate([
                'payment_type' => 'required|in:full_due,partial_paid,full_paid',
                'account_id' => 'nullable|exists:accounts,id|required_if:payment_type,partial_paid,full_paid',
                'amount' => 'nullable|numeric|min:1|required_if:payment_type,partial_paid,full_paid',
                'payment_date' => 'nullable|date|before_or_equal:today',
                'note' => 'nullable|string',
            ], [
                'account_id.required_if' => 'The account field is required when payment type is partial paid or full paid.',
                'amount.required_if' => 'The amount field is required when payment type is partial paid or full paid.',
            ]);
            $purchase = Purchase::findOrFail($purchase);
        }

        try {
            DB::beginTransaction();

            // If payment type is full_due
            if ($request->payment_type == 'full_due') {
                $purchase->update([
                    'due_amount' => $purchase->grand_total,
                    'paid_amount' => 0,
                    'paid_status' => 'full_due',
                ]);

                $payment = $purchase->payment;
                if ($payment) {
                    $payment->update([
                        'due_amount' => $purchase->grand_total,
                        'paid_amount' => 0,
                        'paid_status' => 'full_due',
                    ]);
                }

                DB::commit();

                return response()->json([
                    'message' => 'Purchase recorded as full due successfully.',
                    'type' => 'success',
                    'redirectUrl' => route('admin.purchases.index'),
                ]);
            }

            // For partial_paid or full_paid
            $account = Account::findOrFail($request->account_id);
            if (! $account) {
                return response()->json(['message' => 'Account not found', 'type' => 'error'], 422);
            }

            if ($request->amount > $account->balance) {
                return response()->json(['message' => 'Payment amount cannot be greater than account balance '.$account->balance, 'type' => 'error'], 422);
            }

            if ($request->payment_type == 'partial_paid' && $request->amount > $purchase->due_amount) {
                return response()->json(['message' => 'Payment amount cannot be greater than due amount '.$purchase->due_amount, 'type' => 'error'], 422);
            }

            $latest_due_amount = $purchase->due_amount - $request->amount;
            $total_paid_amount = $purchase->paid_amount + $request->amount;

            $paid_status = $total_paid_amount < $purchase->grand_total ? 'partial_paid' : 'full_paid';

            // Update purchase record
            $purchase->update([
                'due_amount' => $latest_due_amount,
                'paid_amount' => $total_paid_amount,
                'paid_status' => $paid_status,
            ]);

            // Update account balance
            $account->balance = $account->balance - $request->amount;
            $account->save();

            if ($purchase->supplier) {
                $purchase->supplier->balance += $request->amount;
                $purchase->supplier->save();
            }

            if ($request->payment_type == 'full_paid') {
                $amount = $purchase->grand_total - $purchase->paid_amount;
            }

            // Update payment record and add payment detail
            $payment = $purchase->payment;
            if ($payment) {
                $payment->update([
                    'due_amount' => $latest_due_amount,
                    'paid_amount' => $total_paid_amount,
                    'paid_status' => $paid_status,
                ]);

                $payment->paymentDetails()->create([
                    'account_id' => $request->account_id,
                    'amount' => $request->amount ?? $amount,
                    'date' => $request->payment_date,
                    'note' => $request->note,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Payment created successfully.',
                'type' => 'success',
                'redirectUrl' => route('admin.purchases.index'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function view_payments($id)
    {
        $purchase = Purchase::find($id);
        $accounts = Account::select('id', 'name', 'balance')->get();

        return view('backend.purchases.view_payments', compact('purchase', 'accounts'));
    }

    public static function transactionIdGenerate()
    {
        $prefix = 'INV';

        $uniqueNumber = substr(time(), -5).rand(10, 99);

        return $prefix.'-'.$uniqueNumber;
    }
}
