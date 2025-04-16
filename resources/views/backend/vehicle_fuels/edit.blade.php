@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Fueling" />

            <!-- /add -->
            <div class="card">
                <div class="card-body">
                    <form id="updateForm" action="{{ route('admin.vehicle-fuels.update', $vehicleFuel->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Select Vehicle</label>
                                    <select class="select" name="vehicle_id" id="vehicleSelect" required multiple>
                                        <option value="">Select</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}"
                                                {{ $vehicleFuel->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->license_plate }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Type</label>
                                    <select class="select" name="fuel_type" required>
                                        <option value="">Select</option>
                                        <option value="1" {{ $vehicleFuel->fuel_type == '1' ? 'selected' : '' }}>Petrol
                                        </option>
                                        <option value="2" {{ $vehicleFuel->fuel_type == '2' ? 'selected' : '' }}>Diesel
                                        </option>
                                        <option value="3" {{ $vehicleFuel->fuel_type == '3' ? 'selected' : '' }}>Octane
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Current ODO Meter</label>
                                    <input type="number" class="form-control" id="odo_meter" name="current_odometer"
                                    step="0.01" value="{{ $vehicleFuel->current_odometer }}" placeholder="Enter Meter no" required>
                                </div>
                            </div>

                            {{-- <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Date</label>
                                    <div class="input-groupicon calender-input">
                                        <i data-feather="calendar" class="info-img"></i>
                                        <input type="text" class="datetimepicker" placeholder="Choose" name="date"
                                            value="{{ date('Y-m-d', strtotime($vehicleFuel->created_at)) }}" required>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Quantity (Ltr.)</label>
                                    <input type="number" step="0.01" class="form-control" id="fuel_qty" name="fuel_qty"
                                    step="0.01" value="{{ $vehicleFuel->fuel_qty }}" placeholder="Enter quantity" required>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Rate</label>
                                    <input type="number" step="0.01" class="form-control" id="fuel_rate"
                                        name="fuel_rate" value="{{ $vehicleFuel->fuel_rate }}"
                                        placeholder="Enter fuel rate" required>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Total Price</label>
                                    <input type="number" step="0.01" class="form-control" id="total_price"
                                        name="total_price" value="{{ $vehicleFuel->total_price }}"
                                        placeholder="Enter price" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        onclick="window.location.href='{{ route('admin.vehicle-fuels.index') }}'">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /add -->
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
            $('#updateForm').submit(function(e) {
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

                        setTimeout(() => {
                            if (response.redirectUrl) {
                                window.location.href = response.redirectUrl;
                            } else {
                                location.reload();
                            }
                        }, 500);
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
        });
    </script>
@endpush
