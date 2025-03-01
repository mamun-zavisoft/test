@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Services List" button="Add Service" button-route="admin.services.create" />

            <!-- /service list -->
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
                                    <th>Created by</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($purchases as $purchase)
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
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- /service list -->
        </div>
    </div>

@endsection
