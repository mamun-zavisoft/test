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
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>Newest</option>
                                <option>Oldest</option>
                            </select>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="user" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Supplier Name</option>
                                            <option>Apex Computers</option>
                                            <option>Beats Headphones</option>
                                            <option>Dazzle Shoes</option>
                                            <option>Best Accessories</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Status</option>
                                            <option>Received</option>
                                            <option>Ordered</option>
                                            <option>Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="file" class="info-img"></i>
                                        <select class="select">
                                            <option>Enter Reference</option>
                                            <option>PT001</option>
                                            <option>PT002</option>
                                            <option>PT003</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i class="fas fa-money-bill info-img"></i>
                                        <select class="select">
                                            <option>Choose Payment Status</option>
                                            <option>Paid</option>
                                            <option>Partial</option>
                                            <option>Unpaid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                class="feather-search"></i> Search </a>
                                    </div>
                                </div>
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
                                    <th>Created by</th>
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
                                            <span class="badges status-badge bg-warning" data-bs-target="#status-change" data-bs-toggle="modal">Pending</span>
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
                                        @if ($purchase->grand_total == $purchase->paid_amount)
                                            <span class="badge-linesuccess">Paid</span>
                                        @elseif (true)
                                            <span class="badge-linewarning">Partial</span>
                                        @else
                                            <span class="badge-linedanger">Unpaid</span>
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
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body custom-modal-body new-employee-field">
                                                        <form action="{{ route('admin.purchases.statusChange', $purchase) }}" method="POST"
                                                            enctype="multipart/form-data" id="storeForm">
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
                                                                <button type="submit" class="btn btn-submit" id="submit_btn">Save</button>
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

                </div>
            </div>
            <!-- /purchase list -->
        </div>
    </div>

@endsection
