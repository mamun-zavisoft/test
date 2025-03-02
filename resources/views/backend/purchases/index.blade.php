@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Purchase List" sub-title="Manage Your Purchases" button="Add Purchase"
                button-route="admin.purchases.create" />

            <!-- /purchase list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                       
                    </div>
                    
                    <!-- /Filter -->
                    <div class="table-responsive product-list">
                        <table class="table  datanew list">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
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
                                @foreach ($purchases as $purchase)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>{{ $purchase->supplier?->name }}</td>
                                        <td>{{ $purchase->reference_no }}</td>
                                        <td>{{ $purchase->date }}</td>
                                        <td>
                                            @if ($purchase->status == 'pending')
                                                <span class="badges status-badge bg-warning" data-bs-target="#status-change"
                                                    data-bs-toggle="modal">Pending</span>
                                            @elseif ($purchase->status == 'received')
                                                <a href="{{ route('admin.stock-purchases.create', $purchase) }}">
                                                    <span class="badges status-badge bg-info">Store in Drawer</span>
                                                </a>
                                            @elseif ($purchase->status == 'stored')
                                                <span class="badges status-badge bg-success">Stored</span>
                                            @endif
                                        </td>
                                        <td>{{ $purchase->grand_total }}</td>
                                        <td>{{ $purchase->paid_amount }}</td>
                                        <td>{{ $purchase->due_amount }}</td>
                                        <td>
                                            @if ($purchase->paid_status == 'full_due')
                                                <span class="badge-linedanger"
                                                    data-bs-target="#paid-status-{{ $purchase->id }}"
                                                    data-bs-toggle="modal">Due</span>
                                            @elseif ($purchase->paid_status == 'partial_paid')
                                                <span class="badge-linewarning"
                                                data-bs-target="#paid-status-{{ $purchase->id }}"
                                                    data-bs-toggle="modal">Partial Paid</span>
                                            @elseif ($purchase->paid_status == 'full_paid')
                                                <span class="badge-linesuccess">Paid</span>
                                            @else
                                                <span class="badge-linedanger">Not Defined</span>
                                            @endif
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="javascript:void(0);">
                                                    <i data-feather="eye" class="action-eye"></i>
                                                </a>
                                                <a class="me-2 p-2" data-bs-toggle="modal" data-bs-target="#edit-units">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a class="confirm-text p-2" href="javascript:void(0);">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- status change modal  --}}
                                    <div class="modal fade" id="status-change">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header">
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
                                                                action="{{ route('admin.purchases.statusChange', $purchase) }}"
                                                                method="POST" enctype="multipart/form-data"
                                                                id="storeForm">
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

                                    {{-- paid status modal --}}
                                    <div class="modal fade" id="paid-status-{{ $purchase->id }}">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content" style="width: auto; padding-bottom: 50px;">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header">
                                                            <div class="page-title">
                                                                <h4>Purchase Payments</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body new-employee-field">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between">
                                                                        <div>
                                                                            <h5>Supplier:
                                                                                {{ $purchase->supplier?->name }}
                                                                            </h5>
                                                                            <p class="text-muted">Date:
                                                                                {{ $purchase->date }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="text-start">
                                                                            <div class="mb-2">
                                                                                <span class="fw-bold">Total Amount:</span>
                                                                                <span
                                                                                    class="badge bg-success ms-2">{{ number_format($purchase?->grand_total) }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <span class="fw-bold">Paid Amount:</span>
                                                                                <span
                                                                                    class="badge bg-success ms-2">{{ number_format($purchase?->paid_amount) }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="fw-bold">Due Amount:</span>
                                                                                <span
                                                                                    class="badge bg-info ms-2">{{ number_format($purchase?->due_amount) }}</span>
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <span class="fw-bold">Paid Status:</span>
                                                                                <span
                                                                                    class="badge {{ $purchase?->paid_status == 'full_paid' ? 'bg-success' : 'bg-warning' }} ms-2">
                                                                                    {{ $purchase?->paid_status }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Purchase Details -->
                                                            <div class="mt-4">
                                                                <h5 class="mb-3">Payments</h5>
                                                                <div class="d-flex fw-bold border-bottom pb-2">
                                                                    <div class="flex-grow-1" style="width: 150px;">Payment
                                                                        Type</div>
                                                                    <div class="text-center" style="width: 110px;">Amount
                                                                    </div>
                                                                    <div class="text-center" style="width: 110px;">Date
                                                                    </div>
                                                                    <div class="text-center" style="width: 110px;">Note
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex py-2 border-bottom">
                                                                @forelse ($purchase?->payment?->paymentDetails as $paymentDetail)
                                                                    <div class="flex-grow-1" style="width: 150px;">test
                                                                    </div>
                                                                    <div class="text-center" style="width: 110px;">
                                                                        {{ $paymentDetail?->amount }}</div>
                                                                    <div class="text-center" style="width: 110px;">
                                                                        {{ $paymentDetail?->date }}</div>
                                                                    <div class="text-center" style="width: 110px;">
                                                                        {{ $paymentDetail?->note ?? 'N/A' }}</div>
                                                                @empty
                                                                    <p class="text-muted">No payment found</p>
                                                                @endforelse
                                                            </div>
                                                            <div class="text-end mt-3">
                                                                <button type="button" class="btn btn-primary"
                                                                    id="add-payment-btn">Add
                                                                    Payment</button>
                                                                <button type="button" class="btn btn-primary"
                                                                    style="display: none;" id="close-btn">Close Form
                                                                </button>
                                                            </div>
                                                            <div class="payment-form-container mt-3"
                                                                style="display: none;">
                                                                <form class="payment-form" method="POST"
                                                                    action="{{ route('admin.purchases.payment', $purchase->id) }}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="payment_type"
                                                                                class="form-label">Payment Type</label>
                                                                            <select class="select" id="payment_type"
                                                                                name="payment_type" required>
                                                                                <option value="">Select Payment Type
                                                                                </option>
                                                                                <option value="partial_paid">Partial Paid
                                                                                </option>
                                                                                <option value="full_paid">Full Paid
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="payment_type"
                                                                                class="form-label">Accounts</label>
                                                                            <select class="select" id="payment_account"
                                                                                name="account_id" required>
                                                                                <option value="">Select Account
                                                                                </option>
                                                                                @foreach ($accounts as $account)
                                                                                    <option value="{{ $account->id }}">{{ $account->name }}({{ number_format($account->balance) }} ৳)</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3 amount-field"
                                                                            style="display: none;">
                                                                            <label for="amount"
                                                                                class="form-label">Amount</label>
                                                                            <input type="number" class="form-control"
                                                                                id="amount" name="amount"
                                                                                placeholder="Enter amount">
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="payment_date"
                                                                                class="form-label">Payment Date</label>
                                                                            <input type="date" class="form-control"
                                                                                id="payment_date" name="payment_date"
                                                                                value="{{ date('Y-m-d') }}" required>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="note"
                                                                                class="form-label">Note</label>
                                                                            <input type="text" class="form-control"
                                                                                id="note" name="note"
                                                                                placeholder="Payment note (optional)">
                                                                        </div>

                                                                        <div class="col-12 text-end">
                                                                            <button type="button"
                                                                                class="btn btn-secondary me-2"
                                                                                id="cancel-payment-btn">Cancel</button>
                                                                            <button type="submit" class="btn btn-success"
                                                                                id="submit_btn">Submit
                                                                                Payment</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
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
            // Show payment form when Add Payment button is clicked
            $('#add-payment-btn').on('click', function() {
                $('.payment-form-container').show();
                $(this).hide();
                $('#close-btn').show();
            });

            $('#close-btn').on('click', function() {
                $(this).hide();
                $('.payment-form-container').hide();
                $('#add-payment-btn').show();
            })

            // Hide payment form when Cancel button is clicked
            $('#cancel-payment-btn').on('click', function() {
                $('.payment-form-container').hide();
                $('#add-payment-btn').show();
                // Reset form
                $('.editForm')[0].reset();
                $('.amount-field').hide();
            });

            // Show/hide amount field based on payment type selection
            $('#payment_type').on('change', function() {
                if ($(this).val() === 'partial_paid') {
                    $('.amount-field').show();
                    $('#amount').attr('required', true);
                } else {
                    $('.amount-field').hide();
                    $('#amount').attr('required', false);
                }
            });

            // Form submission with AJAX
            $('.payment-form').submit(function(e) {
                e.preventDefault();

                // Disable submit button to prevent multiple submissions
                $('#submit_btn').attr('disabled', true);

                let formData = new FormData(this);

                // For full payment, set amount to due amount
                if ($('#payment_type').val() === 'full_paid') {
                    formData.append('amount', '{{ $purchase->due_amount }}');
                }

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(response) {
                    if (response.type == 'success') {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.href = response.redirectUrl;
                        }, 1000);
                    } else {
                        $('#submit_btn').prop('disabled', false);
                        toastr.error(response.message);
                    }
                }).fail(function(xhr) {
                    $('#submit_btn').attr('disabled', false);
                    let response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                    if (response && response.message) {
                        toastr.error(response.message);
                    }
                });
            });
        });
    </script>
@endpush
