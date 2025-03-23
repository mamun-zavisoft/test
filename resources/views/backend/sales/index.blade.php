@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Sales List" sub-title="Manage Your Sales" button="Add Sale"
                button-route="admin.sales.create" />

            <!-- /filter -->
                <div class="card table-list-card">
                <x-filter />

                    <!-- /Filter -->
                    <div class="table-responsive product-list">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Sale Type</th>
                                    <th>Grand Total</th>
                                    <th>Date</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                    <tr>
                                        <td class="fw-bold">#{{ $sale->transaction_id }}</td>
                                        <td>
                                            @if ($sale->type == "only_sale")
                                                <span class="badge bg-info">POS</span>
                                            @elseif ($sale->type == "external")
                                                <span class="badge bg-warning">External</span>
                                            @else
                                                <span class="badge bg-success">In House</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($sale->grand_total) }}</td>
                                        <td>{{ $sale->created_at?->format('d M Y h:i A') }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#sale-{{ $sale->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye action-eye">
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
                                                <div class="modal-body custom-modal-body new-employee-field" style="flex-grow: 1; overflow-y: auto;">
                                                    
                                                    <!-- Supplier Info -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-8">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h5 class="card-title font-weight-bold mb-3">Customer Info</h5>
                                                                    @if ($sale->type == "only_sale")
                                                                        <p class="mb-1">Sale Type: <span>POS</span></p>
                                                                        <p class="mb-1">Customer Phone: {{ $sale->phone ?? "N/A" }}</p>
                                                                    @elseif ($sale->type == "external")
                                                                        <p class="mb-1">Customer type: <span class="fw-bold">External</span></p>
                                                                    @else
                                                                        <p class="mb-1">Customer type: <span class="fw-bold">In House</span></p>  
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
                                                                        <span class="fw-bolder">#{{ $sale->transaction_id }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <span>Payment Status:</span>
                                                                        <span class="fw-bolder
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
                                                            @foreach($sale->saleDetails as $data)
                                                                <div class="d-flex border-bottom py-2">
                                                                    <div class="p-2" style="flex: 1;">{{ $data->product?->name }}</div>
                                                                    <div class="p-2" style="flex: 1;">{{ number_format($data->unit_price) }}</div>
                                                                    <div class="p-2" style="flex: 1;">{{ $data->qty }}</div>
                                                                    <div class="p-2" style="flex: 1;">{{ number_format($data->qty * $data->unit_price) }}</div>
                                                                </div>
                                                                <?php $total += $data->qty * $data->unit_price ?>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!-- Total Section -->
                                                    <div class="row justify-content-between mt-4">

                                                        <div class="col-md-4">
                                                            <div class="bg-light p-3 rounded">
                                                                <span class="fw-bold">Note</span>
                                                                <p>{{ $sale->note }}</p>
                                                            </div>
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
                                                                    <span class="font-weight-bold">{{ number_format($sale->grand_total) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Footer Actions -->
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
                    </div>
                        {{-- paid status modal --}}
                        <div class="modal fade" id="payment_modal">
                            <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                <div class="modal-content" style="width: auto; padding-bottom: 50px;">
                                    <div class="page-wrapper-new p-0">
                                        <div class="content">
                                            <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                <div class="page-title">
                                                    <h4>Purchase Payments</h4>
                                                </div>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body custom-modal-body new-employee-field"
                                                id="view_payments">
                                                {{-- dynamically show payments --}}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <!-- /purchase list -->
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.payment_view').click(function(e) {
                let url = $(this).data('url');
                let spiner =
                    `<div class="d-flex justify-content-center">   <div class="spinner-border" role="status">     <span class="visually-hidden">Loading...</span>   </div> </div>`
                $('#view_payments').html(spiner);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#view_payments').html(res);
                    }
                })
            }) 
        });
    </script>
@endpush
