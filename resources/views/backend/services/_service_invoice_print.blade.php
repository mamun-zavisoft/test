<!-- Service Invoice Template for Print using DIVs instead of tables -->
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
        flex: 0 0 50%;
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
        flex: 0 0 10%;
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
        border-top: 1px solid #e5e5e5;
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
    .badge-danger {
        background-color: #dc3545;
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
            <h2>FastAuto Clinic</h2>
            <p>Vehicle Service Management</p>
        </div>
        <div class="company-details">
            <h1>SERVICE INVOICE</h1>
            <p>Invoice #: <strong>{{ $service->transaction_id }}</strong></p>
            <p>Date: <strong>{{ $service->created_at?->format('d M Y') }}</strong></p>
        </div>
    </div>

    <!-- Invoice Customer & Vehicle Info -->
    <div class="invoice-row">
        <div class="invoice-col invoice-col-6">
            <div class="invoice-to">
                <h4>Vehicle Information</h4>
                <p><strong>License Plate:</strong> {{ $service->vehicle?->license_plate ?? 'N/A' }}</p>
                <p><strong>Type:</strong> 
                    {{-- <span class="badge {{ $service->vehicle?->owner_type == 1 ? 'badge-success' : 'badge-warning' }}">
                        {{ $service->vehicle?->owner_type == 1 ? 'Self' : 'External' }}
                    </span> --}}
                    <span>{{ $service->vehicle?->owner_type == 1 ? 'Self' : 'External' }}</span>
                </p>
                @if(isset($service->vehicle?->customer))
                <p><strong>Owner:</strong> {{ $service->vehicle?->customer?->name ?? 'N/A' }}</p>
                <p><strong>Contact:</strong> {{ $service->vehicle?->customer?->phone ?? 'N/A' }}</p>
                @endif
            </div>
        </div>
        <div class="invoice-col invoice-col-6">
            <div class="invoice-meta">
                <div class="invoice-meta-item">
                    <div><strong>Service Type:</strong></div>
                    <div>
                        <span>{{ $service->service_type == 1 ? 'Self (In House)' : 'External ' }}</span>
                    </div>
                </div>
                <div class="invoice-meta-item">
                    <div><strong>Payment Status:</strong></div>
                    <div>
                        @if ($service->paid_status == 'full_due')
                            <span class="fw-bold">Due</span>
                        @elseif ($service->paid_status == 'partial_paid')
                            <span class="fw-bold">Partial Paid</span>
                        @elseif ($service->paid_status == 'full_paid')
                            <span class="fw-bold">Paid</span>
                        @elseif ($service->paid_status == 'in_house')
                            <span class="fw-bold">In House</span>
                        @else
                            <span class="fw-bold">Not Defined</span>
                        @endif
                    </div>
                </div>
                <div class="invoice-meta-item">
                    <div><strong>Payment Type:</strong></div>
                    <div>{{ $service->payment_type_id ?? 'Cash' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Details -->
    <div class="invoice-section">
        <div class="invoice-section-title">Service Details</div>
        <div class="invoice-item-header">
            <div class="invoice-col-index">#</div>
            <div class="invoice-col-name">Service Name</div>
            <div class="invoice-col-code">Code</div>
            <div class="invoice-col-price">Amount</div>
        </div>
        
        @foreach($service->serviceDetails as $index => $data)
        <div class="invoice-item">
            <div class="invoice-col-index">{{ $index + 1 }}</div>
            <div class="invoice-col-name">{{ $data->serviceChart?->name }}</div>
            <div class="invoice-col-code">{{ $data->serviceChart?->code }}</div>
            <div class="invoice-col-price">{{ number_format($data->serviceChart?->price) }}</div>
        </div>
        @endforeach
    </div>

    <!-- Parts Used -->
    @if($service->sale && count($service->sale?->saleDetails) > 0)
    <div class="invoice-section">
        <div class="invoice-section-title">Parts Used</div>
        <div class="invoice-item-header">
            <div class="invoice-col-index">#</div>
            <div class="invoice-col-name">Product Name</div>
            <div class="invoice-col-price">Unit Price</div>
            <div class="invoice-col-qty">Quantity</div>
            <div class="invoice-col-total">Total</div>
        </div>
        
        @foreach($service->sale?->saleDetails as $index => $saleDetail)
        <div class="invoice-item">
            <div class="invoice-col-index">{{ $index + 1 }}</div>
            <div class="invoice-col-name">{{ $saleDetail?->product?->name }}</div>
            <div class="invoice-col-price">{{ number_format($saleDetail?->unit_price) }}</div>
            <div class="invoice-col-qty">{{ $saleDetail?->qty }}</div>
            <div class="invoice-col-total">{{ number_format($saleDetail?->qty * $saleDetail->unit_price) }}</div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Invoice Totals -->
    <div class="invoice-totals">
        <div class="invoice-total-row">
            <div><strong>Service Total:</strong></div>
            <div>{{ number_format($service->total_amount) }}</div>
        </div>
        @if($service->discount > 0)
        <div class="invoice-total-row">
            <div><strong>Discount:</strong></div>
            <div>{{ number_format($service->discount) }}</div>
        </div>
        @endif
        <div class="invoice-total-row">
            <div><strong>Grand Total:</strong></div>
            <div>{{ number_format($service->grand_total) }}</div>
        </div>
        <div class="invoice-total-row">
            <div><strong>Paid Amount:</strong></div>
            <div>{{ number_format($service->paid_amount) }}</div>
        </div>
        <div class="invoice-total-row grand-total">
            <div><strong>Due Amount:</strong></div>
            <div>{{ number_format($service->due_amount) }}</div>
        </div>
    </div>

    <!-- Notes if any -->
    @if(!empty($service->notes))
    <div class="invoice-section">
        <div class="invoice-section-title">Additional Notes</div>
        <p>{{ $service->notes }}</p>
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
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</div>