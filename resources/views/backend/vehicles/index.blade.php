<?php $page = 'vehicle-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Vehicle List" sub-title="Manage Vehicle" button="Add Vehicle" modal-id="add-vehicle" />

            <!-- Filter -->
            <div class="card table-list-card">
                    <x-filter>
                        <div class="col-lg-4 col-sm-3 col-12" style="width: 200px;">
                            <div class="mb-3 add-product">
                                <div class="add-newplus">
                                    <label class="form-label">Owner Type</label>
                                </div>
                                <select class="select" name="vehicle_type">
                                    <option value="">Choose</option>
                                    <option value="self" @selected(request()->vehicle_type == 'self')>Self</option>
                                    <option value="external" @selected(request()->vehicle_type == 'external')>External</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-3 col-12 ms-2" style="width: 200px;">
                            <div class="mb-3 add-product">
                                <div class="add-newplus">
                                    <label class="form-label">Zone</label>
                                </div>
                                <select class="select" name="zone_id">
                                    <option value="">Choose</option>
                                    @foreach($zones as $zone)    
                                        <option value="{{$zone->id}}" @selected(request()->zone_id == $zone->id)>{{ $zone->name }}</option>
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                    </x-filter>

                    <!-- /Filter -->

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="no-sort">SL</th>
                                    <th>Owner Type</th>
                                    <th>Vehicle Number</th>
                                    <th>Zone</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @forelse ($vehicles as $vehicle)
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
                                            <a class="me-2 p-2" href="{{ route('admin.vehicles.show', $vehicle->id) }}">
                                                <i data-feather="eye" class="eye-action"></i>
                                            </a>
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
                                                        <div class="modal-header border-0 custom-modal-header justify-content-between">
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
                                                                <!-- <div class="mb-3">
                                                                    <label class="form-label">Owner Type*</label>
                                                                    <select class="select" name="owner_type" disabled>
                                                                        <option value="">Choose</option>
                                                                        <option value="1" {{ $vehicle->owner_type == '1' ? 'selected' : '' }}>Self</option>
                                                                        <option value="2" {{ $vehicle->owner_type == '2' ? 'selected' : '' }}>External</option>
                                                                    </select>
                                                                </div> -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Vehicle Register Number*</label>
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
                                @empty
                                   <tr class="text-center">
                                    <td colspan="7">No Vehicle Found</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <x-pagination :paginator="$vehicles" />
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
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Create Vehicle</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#storeForm')[0].reset()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="text-start ms-3 mb-2">
                        <small>Self=SteadFast Vehicle & External=OutSide Vehicle</small>
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
                                    <label class="form-label">Vehicle Register Number*</label>
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
