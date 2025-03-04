<?php

namespace App\Services;

use App\Http\Controllers\PurchaseController;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function getAllPurchases(int $perPage)
    {
        return Purchase::with('supplier')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function createPurchase(array $data)
    {
        try {
            $grandTotal = $this->calculateGrandTotal($data);

            // Create Purchase Record with initial zero payment
            $purchase = Purchase::create([
                'transaction_id' => PurchaseController::transactionIdGenerate(),
                'zone_id' => auth()->user()?->zone_id,
                'supplier_id' => $data['supplier_id'] ?? null,
                'status' => $data['status'] ?? 'pending',
                'discount_amount' => $data['discount_amount'] ?? 0,
                'shipping_charge' => $data['shipping_charge'] ?? 0,
                'grand_total' => $grandTotal,
                'paid_amount' => 0,
                'due_amount' => $grandTotal,
                'paid_status' => 'full_due',
                'date' => $data['date'] ?? now(),
                'reference_no' => $data['reference_no'] ?? null,
                'note' => $data['note'] ?? null,
            ]);

            // Create Purchase Details
            $this->createPurchaseDetails($purchase, $data);

            $purchase->payment()->create([
                'transaction_type' => 'purchase',
                'grand_total' => $grandTotal,
                'due_amount' => $grandTotal,
                'paid_amount' => 0,
                'paid_status' => 'full_due',
            ]);

            return $purchase;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function calculateGrandTotal(array $data): float
    {
        $products = Product::whereIn('id', $data['product_id'])->pluck('purchase_price', 'id');
        $grandTotal = 0;

        foreach ($data['product_id'] as $index => $productId) {
            if (!isset($products[$productId])) {
                throw new Exception("Product with ID $productId not found.");
            }
            $grandTotal += $data['qty'][$index] * $products[$productId];
        }

        $grandTotal -= $data['discount_amount'] ?? 0;
        $grandTotal += $data['shipping_charge'] ?? 0;

        if ($grandTotal < 0) {
            throw new Exception('Grand total cannot be negative.');
        }

        return $grandTotal;
    }

    private function createPurchaseDetails(Purchase $purchase, array $data)
    {
        $products = Product::whereIn('id', $data['product_id'])->pluck('purchase_price', 'id');
        $purchaseDetails = [];
        $time = now();

        foreach ($data['product_id'] as $index => $productId) {
            $purchaseDetails[] = [
                'purchase_id' => $purchase->id,
                'product_id' => $productId,
                'quantity' => $data['qty'][$index],
                'price' => $products[$productId] * $data['qty'][$index],
                'created_at' => $time,
                'updated_at' => $time,
            ];
        }

        PurchaseDetail::insert($purchaseDetails);
    }
}
