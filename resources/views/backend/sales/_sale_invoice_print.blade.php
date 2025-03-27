<!-- Sales Invoice Template for Print using DIVs instead of tables -->
<style>
    /* Print-specific styles */
    @media print {
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: #fff;
            width: 100%;
            font-size: 14px;
        }
        .page-wrapper, .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .print-only {
            display: block !important;
        }
        .no-print {
            display: none !important;
        }
        .modal, .modal-dialog, .modal-content {
            position: relative;
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: none;
            background: #fff;
            box-shadow: none;
            overflow: visible;
        }
        .invoice-wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
    }

    /* General styles */
    .invoice-wrapper {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        font-family: 'Poppins', sans-serif;
    }
    .invoice-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 20px;
    }
    .invoice-to {
        margin-bottom: 20px;
    }
    .invoice-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }
    .invoice-col {
        padding: 0 15px;
    }
    .invoice-col-6 {
        width: 50%;
        flex: 0 0 50%;
    }
    .invoice-meta {
        margin-bottom: 20px;
        background-color: #f9f9f9;
        border-radius: 5px;
        padding: 15px;
    }
    .invoice-meta-item {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
    }
    .invoice-section {
        margin-bottom: 30px;
        justify-content: space-evenly;
    }
    .invoice-section-title {
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    .invoice-item {
        display: flex;
        border-bottom: 1px solid #e5e5e5;
        padding: 12px 0;
    }
    .invoice-item-header {
        display: flex;
        border-bottom: 2px solid #e5e5e5;
        background-color: #f5f5f5;
        padding: 12px 0;
        font-weight: 600;
    }
    .invoice-col-index {
        flex: 0 0 5%;
    }
    .invoice-col-name {
        flex: 0 0 40%;
    }
    .invoice-col-code {
        flex: 0 0 10%;
    }
    .invoice-col-price {
        flex: 0 0 20%;
        text-align: right;
    }
    .invoice-col-qty {
        flex: 0 0 10%;
        text-align: right;
    }
    .invoice-col-total {
        flex: 0 0 20%;
        text-align: right;
    }
    .invoice-totals {
        margin-top: 20px;
        margin-left: auto;
        width: 300px;
    }
    .invoice-total-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
    }
    .invoice-total-row.grand-total {
        font-size: 18px;
        font-weight: 600;
        color: #0f62fe;
        border-top: 2px solid #e5e5e5;
        padding-top: 10px;
        margin-top: 10px;
    }
    .invoice-footer {
        margin-top: 40px;
        padding-top: 20px;
        font-size: 14px;
        text-align: center;
    }
    .badge {
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        display: inline-block;
    }
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
    .logo-section {
        text-align: left;
    }
    .company-details {
        text-align: right;
    }
    .footer-signature {
        display: flex;
        justify-content: space-between;
        margin-top: 100px;
    }
    .signature-line {
        border-top: 1px solid #ddd;
        width: 200px;
        text-align: center;
        padding-top: 10px;
    }
    .text-right {
        text-align: right;
    }
</style>

<div class="print-only invoice-wrapper">
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="logo-section">
            <img src="{{ asset('build/img/logo.png') }}" class="img-fluid p-2" style="width: 180px; height: 80px" alt="">
            <p>Sales Management System</p>
        </div>
        <div class="company-details">
            <h1>SALES INVOICE</h1>
            <p>Invoice #: <strong>{{ $sale->transaction_id }}</strong></p>
            <p>Date: <strong>{{ $sale->created_at?->format('d M Y') }}</strong></p>
        </div>
    </div>

    <!-- Invoice Customer Info -->
    <div class="invoice-row">
        <div class="invoice-col invoice-col-6">
            <div class="invoice-to">
                <h4>Customer Information</h4>
                @if ($sale->type == "only_sale")
                    <p><strong>Sale Type:</strong> <span>POS</span></p>
                    <p><strong>Customer Phone:</strong> {{ $sale->phone ?? 'N/A' }}</p>
                @elseif ($sale->type == "external")
                    <p><strong>Customer Type:</strong> <span>External</span></p>
                @else
                    <p><strong>Customer Type:</strong> <span>In House</span></p>
                @endif
            </div>
        </div>
        <div class="invoice-col invoice-col-6">
            <div class="invoice-meta">
                <div class="invoice-meta-item">
                    <div><strong>Sale Type:</strong></div>
                    <div>
                        @if ($sale->type == "only_sale")
                            <span>POS</span>
                        @elseif ($sale->type == "external")
                            <span>External</span>
                        @else
                            <span>In House</span>
                        @endif
                    </div>
                </div>
                <div class="invoice-meta-item">
                    <div><strong>Payment Status:</strong></div>
                    <div>
                        @if ($sale->paid_status == 'full_paid')
                            <span class="fw-bold">Paid</span>
                        @else
                            <span class="fw-bold">Unpaid</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Details -->
    <div class="invoice-section">
        <div class="invoice-section-title">Sale Details</div>
        <div class="invoice-item-header">
            <div class="invoice-col-index">#</div>
            <div class="invoice-col-name">Product Name</div>
            <div class="invoice-col-price">Unit Price</div>
            <div class="invoice-col-qty">Quantity</div>
            <div class="invoice-col-total">Total</div>
        </div>
        
        @foreach($sale->saleDetails as $index => $data)
        <div class="invoice-item">
            <div class="invoice-col-index">{{ $index + 1 }}</div>
            <div class="invoice-col-name">{{ $data->product?->name }}</div>
            <div class="invoice-col-price">{{ number_format($data->unit_price) }}</div>
            <div class="invoice-col-qty">{{ $data->qty }}</div>
            <div class="invoice-col-total">{{ number_format($data->qty * $data->unit_price) }}</div>
        </div>
        @endforeach
    </div>

    <!-- Invoice Totals -->
    <div class="invoice-totals">
        <div class="invoice-total-row">
            <div><strong>Total Price:</strong></div>
            <div>{{ number_format($total ?? 0) }}</div>
        </div>
        @if($sale->discount_amount > 0)
        <div class="invoice-total-row">
            <div><strong>Discount:</strong></div>
            <div>{{ number_format($sale->discount_amount) }}</div>
        </div>
        @endif
        <div class="invoice-total-row grand-total">
            <div><strong>Grand Total:</strong></div>
            <div>{{ number_format($sale->grand_total) }}</div>
        </div>
    </div>

    <!-- Notes if any -->
    @if(!empty($sale->note))
    <div class="invoice-section">
        <div class="invoice-section-title">Additional Notes</div>
        <p>{{ $sale->note }}</p>
    </div>
    @endif

    <!-- Footer with Signatures -->
    <div class="footer-signature">
        <div class="signature-line">
            Customer Signature
        </div>
        <div class="signature-line">
            Authorized Signature
        </div>
    </div>

    <!-- Terms and Footer -->
    <div class="invoice-footer">
        <p>Thank you</p>
    </div>
</div>