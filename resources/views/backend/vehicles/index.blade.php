<?php $page = 'vehicle-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Vehicle List" sub-title="Manage Vehicle" button="Add Vehicle" modal-id="add-vehicle" />

           
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

                    <div class="table-responsive">
                        <table class="table  datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">SL</th>
                                    <th>Owner Type</th>
                                    <th>License Plate</th>
                                    <th>Zone</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @foreach ($vehicles as $vehicle)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration + $vehicles->firstItem() - 1 }}
                                        </td>
                                        <td>{{ $vehicle->owner_type == 1 ? 'Self' : 'External' }}</td>
                                        <td>{{ $vehicle->license_plate }}</td>
                                        <td>{{ $vehicle->zone?->name }}</td>
                                        <td><span
                                                class="badge rounded-pill bg-outline-{{ $vehicle->status == 1 ? 'success' : 'warning' }}">{{ $vehicle->status == 1 ? 'Active' : 'In Service' }}</span>
                                        </td>
                                        <td>{{ $vehicle->created_at?->format('d M Y') }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-vehicle-{{ $vehicle->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}"
                                                    method="post" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="confirm-text2 p-2" href="javascript:void(0);">
                                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                                    </a>
                                                </form>
                                            </div>

                                        </td>
                                    </tr>

                                    <!-- Edit Vehicle -->
                                    <div class="modal fade" id="edit-vehicle-{{ $vehicle->id }}">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header">
                                                            <div class="page-title">
                                                                <h4>Edit Vehicle</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body new-employee-field">
                                                            <form class="editForm" data-id="{{ $vehicle->id }}"
                                                                action="{{ route('admin.vehicles.update', $vehicle->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Owner Type*</label>
                                                                    <select class="select" name="owner_type">
                                                                        <option value="">Choose</option>
                                                                        <option value="1" {{ $vehicle->owner_type == '1' ? 'selected' : '' }}>Self</option>
                                                                        <option value="2" {{ $vehicle->owner_type == '2' ? 'selected' : '' }}>External</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">License Plate*</label>
                                                                    <input type="text" class="form-control"
                                                                    value="{{ $vehicle->license_plate }}" name="license_plate">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Status*</label>
                                                                    <select class="select" name="status">
                                                                        <option value="">Choose</option>
                                                                        <option value="1" {{ $vehicle->status == '1' ? 'selected' : '' }}>Active</option>
                                                                        <option value="2" {{ $vehicle->status == '2' ? 'selected' : '' }}>In Service</option>
                                                                    </select>
                                                                </div>
                                                                <div class="modal-footer-btn">
                                                                    <button type="button" class="btn btn-cancel me-2"
                                                                        data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-submit">Save
                                                                        Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit Vehicle -->
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /vehicle list -->
        </div>
    </div>

    <!-- Add Vehicle -->
    <div class="modal fade" id="add-vehicle">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Create Vehicle</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.vehicles.store') }}" method="POST"
                                id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Owner Type*</label>
                                    <select class="select" name="owner_type">
                                        <option value="">Choose</option>
                                        <option value="1">Self</option>
                                        <option value="2">External</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">License Plate*</label>
                                    <input type="text" name="license_plate" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status*</label>
                                    <select class="select" name="status">
                                        <option value="">Choose</option>
                                        <option value="1">Active</option>
                                        <option value="2">In Service</option>
                                    </select>
                                </div>

                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Create Vehicle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ajax call for store
        $(document).ready(function() {
            $('#storeForm').submit(function(e) {
                e.preventDefault();
                let SubmitBtn = $('#submit_btn');
                SubmitBtn.prop('disabled', true);
                let formData = new FormData(this);
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function(response) {
                    if (response.type == 'success') {
                        $('#add-vehicle').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        SubmitBtn.prop('disabled', false);
                        toastr.error(response.message);
                    }
                }).fail(function(xhr) {
                    SubmitBtn.prop('disabled', false);
                    $('#submit_btn').attr('disabled', false);
                    let response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                });
            });

            $('.editForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function(response) {
                    if (response.type == 'success') {
                        $('.edit-vehicle').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
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
                });
            });
        });
    </script>
@endpush
