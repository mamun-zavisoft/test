<table class="table">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Service Type</th>
            <th>Vehicle </th>
            <th>Part Purchased</th>
            <th>Payment Type</th>
            <th>Grand Total</th>
            <th>Paid Status</th>
            <th>Created On</th>
            <th class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($services as $service)
            <tr>
                <td class="fw-bold"><span class="copyable">{{ $service->transaction_id }}</span></td>
                <td>{{ $service->service_type == 'self' ? 'Self' : 'External' }}</td>
                <td><span class="copyable">{{ $service->vehicle?->license_plate }}</span></td>
                <td>
                    <span
                        class="badge rounded-pill {{ $service->any_parts_purchase ? 'bg-outline-success' : 'bg-outline-warning' }}">
                        {{ $service->any_parts_purchase ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td>Cash</td>
                <td>{{ number_format($service->grand_total) }}</td>
                <td>
                    @if ($service->paid_status == 'full_due')
                        <span class="badge-linedanger payment_view"
                            data-url="{{ route('admin.service.view.payments', $service->id) }}"
                            data-bs-target="#payment_modal" data-bs-toggle="modal">Due</span>
                    @elseif ($service->paid_status == 'partial_paid')
                        <span class="badge-linewarning payment_view"
                            data-url="{{ route('admin.service.view.payments', $service->id) }}"
                            data-bs-target="#payment_modal" data-bs-toggle="modal">Partial
                            Paid</span>
                    @elseif ($service->paid_status == 'full_paid')
                        <span class="badge-linesuccess">Paid</span>
                    @elseif ($service->paid_status == 'in_house')
                        <span class="badge-linesuccess">In House</span>
                    @else
                        <span class="badge-linedanger">Not Defined</span>
                    @endif
                </td>
                <td>{{ $service->created_at?->format('d M Y') }}</td>
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="me-2 p-2" href="javascript:void(0);" data-bs-toggle="modal"
                            data-bs-target="#service-{{ $service->id }}">
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
            {{-- detail modal --}}
            <div class="modal fade" id="service-{{ $service->id }}">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%; width: 1400px;">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Service Details</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body custom-modal-body" style="max-height: 90vh; overflow-y: auto;">
                            <!-- Top Section: Vehicle and Invoice Info -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="card h-100">
                                        <div class="card-body ms-4">
                                            <h5 class="card-title fw-bold mb-3">Vehicle Info</h5>
                                            <div class="row mb-2">
                                                <div class="col-md-4 fw-bold">Vehicle Type:</div>
                                                <div class="col-md-8">
                                                    <span
                                                        class="text-{{ $service->service_type == 'self' ? 'success' : 'warning' }}">
                                                        {{ $service->service_type == 'self' ? 'Self' : 'External' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-4 fw-bold">Vehicle Number:</div>
                                                <div class="col-md-8"><span
                                                        class="copyable">{{ $service->vehicle?->license_plate ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold mb-3">Service Info</h5>
                                            <div class="row mb-2">
                                                <div class="col-md-5 fw-bold">Invoice NO:</div>
                                                <div class="col-md-7"><span
                                                        class="copyable">{{ $service->transaction_id }}</span></div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-5 fw-bold">Service Type:</div>
                                                <div class="col-md-7">
                                                    <span
                                                        class="text-{{ $service->service_type == 'self' ? 'success' : 'warning' }}">
                                                        {{ $service->service_type == 'self' ? 'Self' : 'External' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-5 fw-bold">Payment Type:</div>
                                                <div class="col-md-7">{{ $service->payment_type_id ?? 'N/A' }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-5 fw-bold">Payment Status:</div>
                                                <div class="col-md-7">
                                                    <span
                                                        class="badge bg-{{ $service->paid_status == 'full_paid' ? 'success' : 'warning' }}">
                                                        {{ ucwords(str_replace('_', ' ', $service->paid_status ?? 'N/A')) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 fw-bold">Service Date:</div>
                                                <div class="col-md-7">
                                                    {{ $service->created_at?->format('d M Y') ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Details Section -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title fw-bold m-0">Service Details</h5>
                                </div>
                                <div class="card-body ms-2">
                                    <!-- Table Head -->
                                    <div class="d-flex fw-bold border-bottom pb-2">
                                        <div class="p-2" style="flex: 1;">Service Name</div>
                                        <div class="p-2" style="flex: 1;">Unit Price</div>
                                        <div class="p-2" style="flex: 1;">Code</div>
                                    </div>

                                    <!-- Table Body -->
                                    @foreach ($service->serviceDetails as $data)
                                        <div class="d-flex border-bottom py-2">
                                            <div class="p-2" style="flex: 1;">{{ $data->serviceChart?->name }}</div>
                                            <div class="p-2" style="flex: 1;">{{ number_format($data->price) }}
                                            </div>
                                            <div class="p-2" style="flex: 1;">{{ $data->serviceChart?->code }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Products Used Section -->
                            @if ($service->sale)
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title fw-bold m-0">Products Used</h5>
                                    </div>
                                    <div class="card-body ms-2">
                                        <!-- Table Head -->
                                        <div class="d-flex fw-bold border-bottom pb-2">
                                            <div class="p-2" style="flex: 2;">Product Name</div>
                                            <div class="p-2" style="flex: 1;">Price</div>
                                            <div class="p-2" style="flex: 1;">Quantity</div>
                                            <div class="p-2" style="flex: 1;">Total Price</div>
                                        </div>

                                        <!-- Table Body -->
                                        @foreach ($service->sale?->saleDetails as $saleDetail)
                                            <div class="d-flex border-bottom py-2">
                                                <div class="p-2" style="flex: 2;">
                                                    {{ $saleDetail?->product?->name }}</div>
                                                <div class="p-2" style="flex: 1;">
                                                    {{ number_format($saleDetail?->unit_price) }}</div>
                                                <div class="p-2" style="flex: 1;">{{ $saleDetail?->qty }}</div>
                                                <div class="p-2" style="flex: 1;">
                                                    {{ number_format($saleDetail?->qty * $saleDetail->unit_price) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Billing Summary Section -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title fw-bold m-0">Billing Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-end">
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div class="fw-bold">Service Total:</div>
                                                <div>{{ number_format($service->total_amount) }}</div>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <div class="fw-bold">Discount:</div>
                                                <div>{{ number_format($service->discount) }}</div>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <div class="fw-bold">Grand Total:</div>
                                                <div>{{ number_format($service->grand_total) }}</div>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <div class="fw-bold">Paid:</div>
                                                <div>{{ number_format($service->paid_amount) }}</div>
                                            </div>

                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <div class="fw-bold fs-5">Due:</div>
                                                <div class="fw-bold fs-5">{{ number_format($service->due_amount) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes Section -->
                            @if (!empty($service->notes))
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title fw-bold m-0">Additional Notes</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $service->notes }}</p>
                                    </div>
                                </div>
                            @endif

                            <div id="print-invoice-template-{{ $service->id }}" style="display: none;">
                                @include('backend.services._service_invoice_print')
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-secondary me-2"
                                onclick="printInvoice(this, {{ $service->id }})">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-download me-1"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <tr class="text-center">
                <td colspan="9">No Service Found</td>
            </tr>
        @endforelse
    </tbody>
</table>
<x-pagination :paginator="$services" />
