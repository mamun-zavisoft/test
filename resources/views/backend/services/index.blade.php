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
                                    <th>Service Type</th>
                                    <th>Vehicle </th>
                                    <th>Part Purchased</th>
                                    <th>Payment Type</th>
                                    <th>Grand Total</th>
                                    <th>Created On</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>{{ $service->service_type == 'self' ? 'Self' : 'External' }}</td>
                                        <td>{{ $service->vehicle?->license_plate }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $service->any_parts_purchase ? 'bg-outline-success' : 'bg-outline-warning' }}">
                                                {{ $service->any_parts_purchase ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>Cash</td>

                                        <td>{{ $service->grand_total }}</td>
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

                </div>
            </div>
            <!-- /service list -->
        </div>
    </div>
@endsection
