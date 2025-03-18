@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Purchase List" sub-title="Manage Your Purchases" button="Add Purchase"
                button-route="admin.purchases.create" />

            <!--  filter -->
            <div class="card table-list-card">
                    <x-filter />

                    <!-- /Filter -->
                    <div class="table-responsive product-list">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Supplier Name</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Grand Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Paid Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchases as $purchase)
                                    <tr>
                                        <td class="fw-bold">#{{ $purchase->transaction_id }}</td>
                                        <td>{{ $purchase->supplier?->name }}</td>
                                        <td>{{ $purchase->reference_no }}</td>
                                        <td>{{ $purchase->date }}</td>
                                        <td>
                                            @if ($purchase->status == 'pending')
                                                <span class="badges status-badge bg-warning" data-bs-target="#status-change-{{ $purchase->id }}"
                                                    data-bs-toggle="modal">Pending</span>
                                            @elseif ($purchase->status == 'received')
                                                <a href="{{ route('admin.stock-purchases.create', $purchase) }}">
                                                    <span class="badges status-badge bg-info">Store in Drawer</span>
                                                </a>
                                            @elseif ($purchase->status == 'stored')
                                                <span class="badges status-badge bg-success">Stored</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($purchase->grand_total) }}</td>
                                        <td>{{ number_format($purchase->paid_amount) }}</td>
                                        <td>{{ number_format($purchase->due_amount) }}</td>
                                        <td>
                                            @if ($purchase->paid_status == 'full_due')
                                                <span class="badge-linedanger payment_view"
                                                    data-url="{{ route('admin.purchase.view.payments', $purchase->id) }}"
                                                    data-bs-target="#payment_modal" data-bs-toggle="modal">Due</span>
                                            @elseif ($purchase->paid_status == 'partial_paid')
                                                <span class="badge-linewarning payment_view"
                                                    data-url="{{ route('admin.purchase.view.payments', $purchase->id) }}"
                                                    data-bs-target="#payment_modal" data-bs-toggle="modal">Partial
                                                    Paid</span>
                                            @elseif ($purchase->paid_status == 'full_paid')
                                                <span class="badge-linesuccess">Paid</span>
                                            @else
                                                <span class="badge-linedanger">Not Defined</span>
                                            @endif
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#purchase-{{ $purchase->id }}">
                                                    <i data-feather="eye" class="action-eye"></i>
                                                </a>
                                                {{-- <a class="me-2 p-2" data-bs-toggle="modal" data-bs-target="#edit-units">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a class="confirm-text p-2" href="javascript:void(0);">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a> --}}
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- status change modal  --}}
                                    <div class="modal fade" id="status-change-{{ $purchase->id }}">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                            <div class="page-title">
                                                                <h4>Status Change</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body new-employee-field">
                                                            <form
                                                                action="{{ route('admin.purchases.statusChange', $purchase->id) }}"
                                                                method="POST" enctype="multipart/form-data" id="storeForm">
                                                                @csrf
                                                                @method('PUT')
                                                                <select class="select" name="status">
                                                                    <option value="">Choose Status</option>
                                                                    <option value="pending">Pending</option>
                                                                    <option value="received">Received</option>
                                                                </select>

                                                                <div class="modal-footer-btn">
                                                                    <button type="button" class="btn btn-cancel me-2"
                                                                        data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-submit"
                                                                        id="submit_btn">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Product detail modal --}}
                                    <div class="modal fade" id="purchase-{{ $purchase->id }}">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 90%; width: 90%;">
                                            <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
                                                <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                    <div class="page-title">
                                                        <h4>Purchase Details</h4>
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
                                                                    <h5 class="card-title font-weight-bold mb-3">Supplier Info</h5>
                                                                    <p class="mb-1">Supplier Name: {{ $purchase->supplier?->name }}</p>
                                                                    <p class="mb-1">Supplier Number: {{ $purchase->supplier?->phone }}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="card">
                                                                <div class="card-body ms-5">
                                                                    <h5 class="card-title font-weight-bold mb-3">Invoice Info</h5>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <span>Reference:</span>
                                                                        <span class="fw-bolder">{{ $purchase->reference_no }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <span>Payment Status:</span>
                                                                        <span class="fw-bolder text-{{ $purchase->paid_status == 'full_paid' ? 'success' : 'warning' }}">
                                                                            {{ $purchase->paid_status == 'full_paid' ? 'Full Paid' : 'Partial Paid' }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Status:</span>
                                                                        <span class="fw-bolder text-{{ $purchase->status == 'pending' ? 'warning' : 'success' }}">
                                                                            {{ $purchase->status == 'pending' ? 'Pending' : 'Received' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Purchase Details -->
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <h5 class="card-title font-weight-bold mb-4">Purchase Details</h5>

                                                            <!-- Table Head -->
                                                            <div class="d-flex fw-bold border-bottom pb-2">
                                                                <div class="p-2" style="flex: 1;">Product Name</div>
                                                                <div class="p-2" style="flex: 1;">Quantity</div>
                                                                <div class="p-2" style="flex: 1;">Purchase Price</div>
                                                                <div class="p-2" style="flex: 1;">Sale Price</div>
                                                                <div class="p-2" style="flex: 1;">Total Price</div>
                                                            </div>

                                                            <!-- Table Body -->
                                                            @foreach($purchase->purchaseDetails as $data)
                                                                <div class="d-flex border-bottom py-2">
                                                                    <div class="p-2" style="flex: 1;">{{ $data->product?->name }}</div>
                                                                    <div class="p-2" style="flex: 1;">{{ $data->quantity }}</div>
                                                                    <div class="p-2" style="flex: 1;">{{ $data->product->purchase_price }}</div>
                                                                    <div class="p-2" style="flex: 1;">{{ $data->product->sale_price }}</div>
                                                                    <div class="p-2" style="flex: 1;">{{ $data->quantity * $data->product->purchase_price }}</div> 
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!-- Total Section -->
                                                    <div class="row justify-content-end mt-4">
                                                        <div class="col-md-5">
                                                            <div class="bg-light p-3 rounded">
                                                                <div class="d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Total Price</span>
                                                                    <span class="font-weight-bold">{{ $purchase->grand_total }}</span>
                                                                </div>
                                                                <div class="d-flex justify-content-between">
                                                                    <span>Shipping Charge</span>
                                                                    <span>{{ $purchase->shipping_charge }}</span>
                                                                </div>
                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <span>Discount</span>
                                                                    <span>{{ $purchase->discount_amount }}</span>
                                                                </div>
                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <span>Grand Total Price</span>
                                                                    <span>{{ ($purchase->grand_total + $purchase->shipping_charge) - $purchase->discount_amount }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Footer Actions -->
                                                <div class="modal-footer d-flex justify-content-end">
                                                    <button class="btn btn-secondary me-2" onclick="window.print()">
                                                        <i class="fa fa-print me-1"></i> Print
                                                    </button>
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-download me-1"></i> Download PDF
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                   <tr class="text-center">
                                    <td colspan="7">No Product Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :paginator="$purchases" />
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
