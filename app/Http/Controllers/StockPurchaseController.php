<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Rack;
use Exception;
use Illuminate\Http\Request;

class StockPurchaseController extends Controller
{
    public function create($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            $products = $purchase->products;
            $racks = Rack::with('drawers')->select('id', 'name')->get();
            return view('backend.stock_purchases.create', compact('purchase', 'products', 'racks'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
