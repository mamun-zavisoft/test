<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Invoice No</th>
            <th>Sale Type</th>
            <th>Grand Total</th>
            <th>Paid Status</th>
            <th>Date</th>
            <th class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($sales as $sale)
            <tr>
                <td>{{ $loop->iteration + $sales->firstItem() - 1 }}</td>
                <td class="fw-bold"><span class="copyable">{{ $sale->transaction_id }}</span></td>
                <td>
                    @if ($sale->type == 'only_sale')
                        <span class="badge bg-info">POS</span>
                    @elseif ($sale->type == 'external')
                        <span class="badge bg-warning">External</span>
                    @else
                        <span class="badge bg-success">In House</span>
                    @endif
                </td>
                <td>{{ number_format($sale->grand_total) }}</td>
                <td>
                    @if ($sale->paid_status == 'full_due')
                        <span class="badge-linedanger payment_view">Due</span>
                    @elseif ($sale->paid_status == 'partial_paid')
                        <span class="badge-linewarning payment_view">Partial
                            Paid</span>
                    @elseif ($sale->paid_status == 'full_paid')
                        <span class="badge-linesuccess">Paid</span>
                    @elseif ($sale->paid_status == 'in_house')
                        <span class="badge-linesuccess">In House</span>
                    @else
                        <span class="badge-linedanger">Not Defined</span>
                    @endif
                </td>
                <td>{{ $sale->created_at?->format('d M Y h:i A') }}</td>
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="me-2 p-2" href="javascript:void(0);" data-bs-toggle="modal"
                            data-bs-target="#sale-{{ $sale->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-eye action-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </a>

                    </div>
                </td>
            </tr>

            {{-- sale detail modal --}}
            <div class="modal fade" id="sale-{{ $sale->id }}">
                <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 90%; width: 80%;">
                    <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Sale Details</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field"
                            style="flex-grow: 1; overflow-y: auto;">

                            <!-- Supplier Info -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title font-weight-bold mb-3">Customer Info</h5>
                                            @if ($sale->type == 'only_sale')
                                                <p class="mb-1">Sale Type: <span>POS</span></p>
                                                <p class="mb-1">Customer Phone: {{ $sale->phone ?? 'N/A' }}</p>
                                            @elseif ($sale->type == 'external')
                                                <p class="mb-1">Customer type: <span class="fw-bold">External</span>
                                                </p>
                                            @else
                                                <p class="mb-1">Customer type: <span class="fw-bold">In House</span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title font-weight-bold mb-3">Invoice Info</h5>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Invoice:</span>
                                                <span class="fw-bolder copyable">{{ $sale->transaction_id }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Payment Status:</span>
                                                <span
                                                    class="fw-bolder
                                                    text-{{ $sale->paid_status == 'full_paid' ? 'success' : 'warning' }}">
                                                    {{ $sale->paid_status == 'full_paid' ? 'Paid' : 'Unpaid' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Purchase Details -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold mb-4">Sale Details</h5>

                                    <!-- Table Head -->
                                    <div class="d-flex fw-bold border-bottom pb-2">
                                        <div class="p-2" style="flex: 1;">Product Name</div>
                                        <div class="p-2" style="flex: 1;">Unit Price</div>
                                        <div class="p-2" style="flex: 1;">Quantity</div>
                                        <div class="p-2" style="flex: 1;">Total Price</div>
                                    </div>

                                    <!-- Table Body -->
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($sale->saleDetails as $data)
                                        <div class="d-flex border-bottom py-2">
                                            <div class="p-2" style="flex: 1;">{{ $data->product?->name }}</div>
                                            <div class="p-2" style="flex: 1;">{{ number_format($data->unit_price) }}
                                            </div>
                                            <div class="p-2" style="flex: 1;">{{ $data->qty }}</div>
                                            <div class="p-2" style="flex: 1;">
                                                {{ number_format($data->qty * $data->unit_price) }}</div>
                                        </div>
                                        <?php $total += $data->qty * $data->unit_price; ?>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Total Section -->
                            <div class="row justify-content-between mt-4">

                                <div class="col-md-4">
                                    @if ($sale->note != null)
                                        <div class="bg-light p-3 rounded">
                                            <span class="fw-bold">Note</span>
                                            <p>{{ $sale->note }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <div class="bg-light p-3 rounded">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Price</span>
                                            <span>{{ number_format($total) ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Discount</span>
                                            <span>{{ number_format($sale->discount_amount) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Grand Total</span>
                                            <span
                                                class="font-weight-bold">{{ number_format($sale->grand_total) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Print Invoice --}}
                        <div id="print-invoice-template-{{ $sale->id }}" style="display: none;">
                            @include('backend.sales._sale_invoice_print')
                        </div>

                        <!-- Footer Actions -->
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-secondary me-2"
                                onclick="printInvoice(this, {{ $sale->id }})">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <tr class="text-center">
                <td colspan="7">No Sale Found</td>
            </tr>
        @endforelse
    </tbody>
</table>
<x-pagination :paginator="$sales" />
