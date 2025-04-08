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
    <div class="modal fade" id="add-vehicle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 rounded-4 shadow-lg" style="max-height: 100vh;">
                <div class="modal-header bg-gradient text-white rounded-top-4 justify-content-between"
                    style="background: linear-gradient(90deg, #007bff, #0056b3);">
                    <div class="page-title">
                        <h5 class="modal-title fw-bold">Add Vehicle </h5>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                     onclick="$('#storeForm')[0].reset()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <!-- <div class="mb-3">
                        <small class="text-muted">Self = SteadFast Vehicle & External = Outside Vehicle</small>
                    </div> -->

                    <form action="{{ route('admin.vehicles.store') }}" method="POST" id="storeForm">
                        @csrf
                        <div class="accordion" id="vehicleAccordion">
                            <!-- Accordion 1: Basic Info -->
                            <div class="accordion-item mb-3 rounded-3 overflow-hidden border border-1">
                                <h2 class="accordion-header" id="basicInfoHeading">
                                    <button class="accordion-button fw-bold p-3" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#basicInfo" aria-expanded="true" aria-controls="basicInfo">
                                        Vehicle Basic Info
                                    </button>
                                </h2>
                                <div id="basicInfo" class="accordion-collapse collapse show" aria-labelledby="basicInfoHeading"
                                    data-bs-parent="#vehicleAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Owner Type<span class="text-danger">*</span></label>
                                                <select name="owner_type" class="form-select">
                                                    <option value="">Choose</option>
                                                    <option value="1" selected>Self</option>
                                                    <option value="2">External</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Register Number<span class="text-danger">*</span></label>
                                                <input type="text" name="license_plate" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Vehicle Type<span class="text-danger">*</span></label>
                                                <select name="vehicle_type" class="form-select">
                                                    <option value="">Choose</option>
                                                    <option value="1">Covered Van</option>
                                                    <option value="2">Motor Bike</option>
                                                    <option value="3">Pick Up</option>
                                                    <option value="4">Truck</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">ODO (current odometer)<span class="text-danger">*</span></label>
                                                <input type="number" name="current_odometer" class="form-control"
                                                    placeholder="Current Mileage" onwheel="this.blur()">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Select Hub</label>
                                                <select name="hub_id" class="form-select">
                                                    <option value="">Choose</option>
                                                    @foreach ($hubs as $hub)
                                                    <option value="{{ $hub->id }}">{{ $hub->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Select Model</label>
                                                <select name="vehicle_model_id" class="form-select">
                                                    <option value="">Choose</option>
                                                    @foreach ($vehicleModels as $model)
                                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="">Choose</option>
                                                    <option value="1">Active</option>
                                                    <option value="2">In Service</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Accordion 2: Date Info -->
                            <div class="accordion-item rounded-3 overflow-hidden border border-1">
                                <h2 class="accordion-header" id="dateInfoHeading">
                                    <button class="accordion-button collapsed fw-bold p-3" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dateInfo" aria-expanded="false" aria-controls="dateInfo">
                                        Vehicle Date Information
                                    </button>
                                </h2>
                                <div id="dateInfo" class="accordion-collapse collapse" aria-labelledby="dateInfoHeading"
                                    data-bs-parent="#vehicleAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Registration Date</label>
                                                <input type="date" name="registration_date" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Registration Validity</label>
                                                <input type="date" name="registration_validity" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Tax Token Validity</label>
                                                <input type="date" name="tax_token_validity" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Fitness Validity</label>
                                                <input type="date" name="fitness_validity" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Road Permit Validity</label>
                                                <input type="date" name="road_permit_validity" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Insurance Validity</label>
                                                <input type="date" name="insurance_validity" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="modal-footer d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary py-1 px-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success py-1 px-2" id="submit_btn">Save</button>
                        </div>
                    </form>
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
                    // Hide all form groups inside accordion body
                    $('#add-vehicle .accordion-body .col-md-6').hide();
                    // Show only selected fields
                    $('select[name="owner_type"]').closest('.col-md-6').show();
                    $('input[name="license_plate"]').closest('.col-md-6').show();
                    $('select[name="vehicle_type"]').closest('.col-md-6').show();
                } else {
                    // Show all fields again
                    $('#add-vehicle .accordion-body .col-md-6').show();
                }
            });

            // Trigger change event on page load to set initial visibility
            $('select[name="owner_type"]').trigger('change');
        });
    </script>
@endpush
