@extends('layout.mainlayout')
@section('content')
    <style>
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .status-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-good {
            background-color: #d1edff;
            color: #0c5460;
            border: 1px solid #b8daff;
        }

        .status-excellent {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-icon {
            width: 12px;
            height: 12px;
        }

        .odo-info-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 8px 10px;
            margin-top: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .info-label {
            color: #6c757d;
            font-size: 11px;
        }

        .info-value {
            color: #495057;
            font-size: 11px;
            font-weight: 500;
        }
    </style>

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
                                    <select class="select" name="vehicle_id" id="vehicleSelect" required>
                                        <option value="">Select Vehicle</option>
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

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="input-blocks">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label class="mb-0">Current ODO Meter (KM)</label>
                                                <div id="mileage_status" class="d-flex align-items-center"></div>
                                            </div>
                                            <input type="number" class="form-control" id="odo_meter"
                                                name="current_odometer" step="0.01" placeholder="Enter ODO Meter"
                                                required>

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">

                                        <div id="odo_info" class="mt-2 small text-muted"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Quantity (Ltr.)</label>
                                    <input type="number" class="form-control" id="fuel_qty" name="fuel_qty" step="0.01"
                                        placeholder="Enter quantity" onwheel="this.blur()" required>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Fuel Rate</label>
                                    <input type="number" class="form-control" id="fuel_rate" name="fuel_rate"
                                        placeholder="Enter fuel rate" onwheel="this.blur()" required>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Total Price</label>
                                    <input type="number" class="form-control" id="total_price" name="total_price"
                                        placeholder="Enter price" readonly>

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
            $('#vehicleSelect').select2({
                placeholder: "Select a vehicle...",
            });
            
            $("#vehicleSelect").on("change", function() {

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
                            updateMileageStatus(response);
                            updateOdoInfo(response);
                            $('#odo_meter').attr('min', response.vehicle.current_odometer);
                        },
                        error: function() {
                            toastr.error('Failed to fetch fueling history');
                            $('#odo_meter').attr('placeholder', 'Enter ODO Meter');
                            $('#odo_meter').attr('min', 0);
                            clearMileageStatus();
                        }
                    });
                } else {
                    $('#odo_meter').attr('placeholder', 'Enter ODO Meter');
                    $('#odo_meter').attr('min', 0);
                    $('#odo_meter').val('');
                    clearMileageStatus();
                }
                setTimeout(() => {
                    $(this).data('processing', false);
                }, 100);
            });

            function updateMileageStatus(response) {
                let model_data = response.vehicle_model;
                let vehicle = response.vehicle;
                let statusHtml = '';

                if (vehicle.mileage === 0) {
                    // Show nothing for 0 mileage
                    statusHtml = '';
                } else if (model_data && vehicle.mileage < model_data.avg_mileage) {
                    // Warning for below average
                    statusHtml = `
                        <div class="status-badge status-warning">
                            <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Below Average
                        </div>
                    `;
                } else if (model_data && vehicle.mileage >= model_data.avg_mileage && vehicle.mileage < (model_data
                        .avg_mileage * 1.2)) {
                    // Good mileage
                    statusHtml = `
                        <div class="status-badge status-good">
                            <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Good Mileage
                        </div>
                    `;
                } else if (model_data && vehicle.mileage >= (model_data.avg_mileage * 1.2)) {
                    // Excellent mileage
                    statusHtml = `
                        <div class="status-badge status-excellent">
                            <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Excellent
                        </div>
                    `;
                }

                $('#mileage_status').html(statusHtml);
            }

            function updateOdoInfo(response) {
                let vehicle = response.vehicle;
                let model_data = response.vehicle_model;

                let infoHtml = `
                    <div class="odo-info-card">
                        <div class="info-row">
                            <span class="info-label">Last ODO :</span>
                            <span class="info-value">${vehicle.current_odometer} KM</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Last Mileage :</span>
                            <span class="info-value">${vehicle.mileage} KM/L</span>
                        </div>
                        ${model_data ? `
                        <div class="info-row">
                            <span class="info-label">Model :</span>
                            <span class="info-value">${model_data.name}</span>
                        </div>
                        ` : ''}
                        ${model_data ? `
                        <div class="info-row">
                            <span class="info-label">Avg Mileage :</span>
                            <span class="info-value">${model_data.avg_mileage} KM/L</span>
                        </div>
                        ` : ''}
                    </div>
                `;

                $('#odo_info').html(infoHtml);
                $('#odo_meter').attr('placeholder', 'Enter current ODO reading');
            }

            function clearMileageStatus() {
                $('#mileage_status').html('');
                $('#odo_info').html('');
            }

            // Alternative approach using select2 events if you're using Select2
            $("#vehicleSelect").on("select2:select", function(e) {
                // Get the newly selected option
                let selectedOption = e.params.data;

                // Clear all selections and select only the new one
                $(this).val([selectedOption.id]).trigger('change.select2');
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
            $('#storeForm').submit(async function(e) {
                e.preventDefault();

                $confirm = await Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to update fueling later!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel'
                });

                if (!$confirm.isConfirmed) {
                    return false;
                }

                let SubmitBtn = $('#submit_btn');
                SubmitBtn.prop('disabled', true);
                let formData = new FormData(this);

                // Convert array to single value for form submission
                let selectedVehicle = $('#vehicleSelect').val();
                if (selectedVehicle && selectedVehicle.length > 0) {
                    formData.set('vehicle_id', selectedVehicle[
                        0]); // Take only the first (and should be only) selected value
                }

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

            // Validation functions remain the same
            $('#fuel_qty').on('keypress', function() {
                if (event.key === '-') {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'Fuel quantity cannot be negative!',
                        didOpen: () => {
                            const popup = document.querySelector('.swal2-popup');
                            popup.style.width = '400px';
                            popup.style.height = '170px';
                        }
                    });
                }
            });

            $('#fuel_rate').on('keypress', function() {
                if (event.key === '-') {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'Fuel rate cannot be negative!',
                        didOpen: () => {
                            const popup = document.querySelector('.swal2-popup');
                            popup.style.width = '400px';
                            popup.style.height = '170px';
                        }
                    });
                }
            });

            $('#odo_meter').on('keypress', function() {
                if (event.key === '-') {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'Odo meter cannot be negative!',
                        didOpen: () => {
                            const popup = document.querySelector('.swal2-popup');
                            popup.style.width = '400px';
                            popup.style.height = '170px';
                        }
                    });
                }
            });
        });
    </script>
@endpush
