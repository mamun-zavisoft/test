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
                                    {{-- <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th> --}}
                                    <th>Invoice No</th>
                                    <th>Service Type</th>
                                    <th>Vehicle </th>
                                    <th>Part Purchased</th>
                                    <th>Payment Type</th>
                                    <th>Grand Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Paid Status</th>
                                    <th>Created On</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        {{-- <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td> --}}
                                        <td class="fw-bold">#{{ $service->transaction_id }}</td>
                                        <td>{{ $service->service_type == 'self' ? 'Self' : 'External' }}</td>
                                        <td>{{ $service->vehicle?->license_plate }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $service->any_parts_purchase ? 'bg-outline-success' : 'bg-outline-warning' }}">
                                                {{ $service->any_parts_purchase ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>Cash</td>

                                        <td>{{ $service->grand_total }}</td>
                                        <td>{{ $service->paid_amount }}</td>
                                        <td>{{ $service->due_amount }}</td>
                                        <td>
                                            {{-- @if ($service->paid_status == 'full_due')
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
                                            @else
                                                <span class="badge-linedanger">Not Defined</span>
                                            @endif --}}
                                            <span class="badge-linedanger">Due</span>
                                        </td>
                                        <td>{{ $service->created_at?->format('d M Y') }}</td>
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
                                                    <h4>Service Payments</h4>
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
            <!-- /service list -->
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