@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Fueling" />

            <!-- /add -->
            <div class="card">
                <div class="card-body">
                    <form id="storeForm" action="{{ route('admin.vehicle-fuels.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Select Vehicle</label>
                                    <select class="select" name="vehicle_id" id="vehicleSelect" required multiple>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Type</label>
                                    <select class="select" name="fuel_type" id="fuel_type" required>
                                        <option value="">Select</option>
                                        <option value="1">Diesel</option>
                                        <option value="2">Petrol</option>
                                        <option value="3">Octane</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Current ODO Meter (KM)</label>
                                    <input type="text" class="form-control" id="odo_meter" name="current_odometer"
                                        placeholder="Enter ODO Meter" required>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Quantity (Ltr.)</label>
                                    <input type="number" class="form-control" id="fuel_qty" name="fuel_qty"
                                        placeholder="Enter quantity" onwheel="this.blur()" required>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Rate</label>
                                    <input type="number" class="form-control" id="fuel_rate"
                                        name="fuel_rate" placeholder="Enter fuel rate" onwheel="this.blur()" required>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Total Price</label>
                                    <input type="number" class="form-control" id="total_price"
                                        name="total_price" placeholder="Enter price" readonly>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="modal-footer-btn" style="margin-top: 0 ! important">
                                    <button type="button" class="btn btn-cancel me-2"
                                        onclick="window.location.href='{{ route('admin.vehicle-fuels.index') }}'">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /add -->

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Recent Fueling</h4>
                    <a href="{{ route('admin.vehicle-fuels.index') }}" class="btn btn-sm btn-primary float-end">See All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive product-list" id="dataTable">
                        <x-vehicleFuels.table :entity="$recentFuelings" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#vehicleSelect").on("change", function() { 
                let selectedOptions = $(this).find("option:selected");

                if (selectedOptions.length > 1) {
                    // Deselect all selected options except the last one
                    selectedOptions.each(function(index, option) {
                        if (index < selectedOptions.length - 1) {
                            $(option).prop("selected", false);
                        }
                    });

                    $(this).trigger("change");
                }

                let selectedVehicleId = $(this).val();
                if (selectedVehicleId > 0) {
                    $.ajax({
                        url: "{{ route('admin.vehicle.getCurrentOdometer') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            vehicle_id: selectedVehicleId
                        },
                        success: function(response) {
                            $('#odo_meter').attr('placeholder',
                                `Last ODO Meter was: ${response.current_odometer} KM`);
                            $('#odo_meter').attr('min', response.current_odometer);
                        },
                        error: function() {
                            toastr.error('Failed to fetch fueling history');
                            $('#odo_meter').attr('placeholder', 'Enter ODO Meter');
                            $('#odo_meter').attr('min', 0);
                        }
                    });
                } else {
                    $('#odo_meter').attr('placeholder', 'Enter ODO Meter');
                    $('#odo_meter').attr('min', 0);
                    $('#odo_meter').val('');
                }
            });

            $('#fuel_qty, #fuel_rate').on('input', function() {
                calculateTotalPrice();
            });

            // Function to calculate total price
            function calculateTotalPrice() {
                let qty = parseFloat($('#fuel_qty').val()) || 0;
                let rate = parseFloat($('#fuel_rate').val()) || 0;
                let total = (qty * rate).toFixed(2);
                $('#total_price').val(total);
            }

            // Form submission with AJAX
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
                        toastr.success(response.message);
                        $('#submit_btn').attr('disabled', false);

                        let recentFuelings = $(response.latestFuelingsHtml);

                        $('#dataTable').html(recentFuelings);
                        // reset form
                        $('#storeForm')[0].reset();
                        $('#vehicleSelect').trigger("change");
                        $('#fuel_type').trigger("change");
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
                    if (response && response.message) {
                        toastr.error(response.message);
                    }
                });
            });
            
            // Don't get negative values in fuel qty
            $('#fuel_qty').on('keypress', function (e) {
                if (e.key === '-' ){
                    e.preventDefault();
                }
            });

            // Don't get negative values in fuel rate
            $('#fuel_rate').on('keypress', function (e) {
                if (e.key === '-' ){
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
