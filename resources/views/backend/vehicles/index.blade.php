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
                                <select class="select filter-input" name="vehicle_type">
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
                                <select class="select filter-input" name="zone_id">
                                    <option value="">Choose</option>
                                    @foreach($zones as $zone)    
                                        <option value="{{$zone->id}}" @selected(request()->zone_id == $zone->id)>{{ $zone->name }}</option>
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                    </x-filter>

                    <!-- /Filter -->

                    <div class="table-responsive" id="dataTable">
                        <x-vehicles.table :entity="$vehicles"/>
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
