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
                        <!-- <div class="col-lg-4 col-sm-3 col-12 ms-2" style="width: 200px;">
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
                        </div> -->
                        <div class="col-lg-4 col-sm-3 col-12 ms-2" style="width: 200px;">
                            <div class="mb-3 add-product">
                                <div class="add-newplus">
                                    <label class="form-label">Hub</label>
                                </div>
                                <select class="select filter-input" name="hub_id">
                                    <option value="">Choose</option>
                                    @foreach ($hubs as $hub)
                                    <option value="{{$hub->id}}" @selected(request()->hub_id == $hub->id)>{{ $hub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-3 col-12 ms-2" style="width: 200px;">
                            <div class="mb-3 add-product">
                                <div class="add-newplus">
                                    <label class="form-label">Vehicle Model</label>
                                </div>
                                <select class="select filter-input" name="vehicle_model_id">
                                    <option value="">Choose</option>
                                    @foreach ($vehicleModels as $vehicleModel)
                                    <option value="{{$vehicleModel->id}}" @selected(request()->vehicle_model_id == $vehicleModel->id)>{{ $vehicleModel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-filter>

                    <!-- /Filter -->

                    <div class="table-responsive" id="dataTable">
                        <x-vehicles.table :vehicles="$vehicles" :zones="$zones" :vehicleModels="$vehicleModels" :hubs="$hubs"/>
                    </div>
                </div>
            </div>
            <!-- /vehicle list -->
        </div>
    </div>

    <!-- Add Vehicle -->
    <div class="modal fade" id="add-vehicle">
        <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 95%; width: 1400px; max-height: 95vh; height: 90vh;">
            <div class="modal-content" style="height: 100%;"> 
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
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Owner Type*</label>
                                        <select class="select form-control" name="owner_type">
                                            <option value="">Choose</option>
                                            <option value="1">Self</option>
                                            <option value="2">External</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Register Number*</label>
                                        <input type="text" name="license_plate" class="form-control">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Status</label>
                                        <select class="select form-control" name="status">
                                            <option value="">Choose</option>
                                            <option value="1">Active</option>
                                            <option value="2">In Service</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Select Hub</label>
                                        <select class="select form-control" name="hub_id">
                                            <option value="">Choose</option>
                                            @foreach ($hubs as $hub)
                                            <option value="{{$hub->id}}">{{ $hub->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Vehicle Type</label>
                                        <select class="select form-control" name="vehicle_type">
                                            <option value="">Choose</option>
                                            <option value="1">Covered Van</option>
                                            <option value="2">Motor Bike</option>
                                            <option value="3">Pick Up</option>
                                            <option value="4">Truck</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Select Model</label>
                                        <select class="select form-control" name="vehicle_model_id">
                                            <option value="">Choose</option>
                                            @foreach ($vehicleModels as $vehicleModel)
                                            <option value="{{ $vehicleModel->id }}">{{ $vehicleModel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">ODO(current odometer)</label>
                                        <input type="number" name="current_odometer" class="form-control" placeholder="Current Mileage">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Registration Date</label>
                                        <input type="date" name="registration_date" class="form-control">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Registration Validity</label>
                                        <input type="date" name="registration_validity" class="form-control">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Tax Token Validity</label>
                                        <input type="date" name="tax_token_validity" class="form-control">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Fitness Validity</label>
                                        <input type="date" name="fitness_validity" class="form-control">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Road Permit Validity</label>
                                        <input type="date" name="road_permit_validity" class="form-control">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Insurance Validity</label>
                                        <input type="date" name="insurance_validity" class="form-control">
                                    </div>
                                </div>
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
                        $('#edit-vehicle').modal('hide');
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

            $('select[name="owner_type"]').change(function () {
                var ownerType = $(this).val();

                if (ownerType === "2") { // External selected
                    $('div.mb-3').hide(); // Hide all fields
                    $('select[name="owner_type"]').closest('div.mb-3').show(); // Keep owner type visible
                    $('input[name="license_plate"]').closest('div.mb-3').show(); // Show Register Number
                    $('select[name="vehicle_type"]').closest('div.mb-3').show(); // Show Vehicle Type
                } else { // Self selected
                    $('div.mb-3').show(); // Show all fields
                }
            });
        });
    </script>
@endpush
