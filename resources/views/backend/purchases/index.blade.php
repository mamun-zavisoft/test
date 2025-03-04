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
                                    {{-- <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th> --}}
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
                                @foreach ($purchases as $purchase)
                                    <tr>
                                        {{-- <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td> --}}
                                        <td class="fw-bold">#{{ $purchase->transaction_id }}</td>
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

                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        {{-- paid status modal --}}
                        <div class="modal fade" id="payment_modal">
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
