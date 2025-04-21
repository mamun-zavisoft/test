@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Vehicle Service Report</h3>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="reportFilterForm">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label>Date Range</label>
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="dateRangeSelect">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                    <input type="text" 
                                           class="form-select daterange" 
                                           id="dateRangeSelect"
                                           value="{{ $startDate }} - {{ $endDate }}"
                                           style=" cursor: pointer !important;"
                                           readonly>
                                </div>
                                <input type="hidden" name="start_date" id="start_date" value="{{ $startDate }}">
                                <input type="hidden" name="end_date" id="end_date" value="{{ $endDate }}">
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <label>Vehicle Type</label>
                                <select class="form-control select2" name="vehicle_type" id="vehicle_type">
                                    <option value="">All Types</option>
                                    <option value="1">Self</option>
                                    <option value="2">External</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label>Vehicle</label>
                                <select class="form-control select2" name="vehicle_id" id="vehicle_id">
                                    <option value="">All Vehicles</option>
                                    @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" data-type="{{ $vehicle->owner_type ?? '' }}">{{ $vehicle->license_plate }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 d-flex align-items-center">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card dash-widget text-center">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-car"></i></span>
                        <div class="dash-widget-info text-center w-100">
                            <h3 id="totalVehicles">0</h3>
                            <span>Service Taken</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dash-widget text-center">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-wrench"></i></span>
                        <div class="dash-widget-info text-center w-100">
                            <h3 id="totalServiceCost">0 ৳</h3>
                            <span>Total Service Cost</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dash-widget text-center">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-gas-pump"></i></span>
                        <div class="dash-widget-info text-center w-100">
                            <h3 id="totalFuelQty">0 Ltr</h3>
                            <span>Total Fuel</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dash-widget text-center">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-money-bill-wave"></i></span>
                        <div class="dash-widget-info text-center w-100">
                            <h3 id="totalFuelCost">0 ৳</h3>
                            <span>Total Fuel Cost</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Service Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Reg No.</th>
                                        <th>Services Count</th>
                                        <th>Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody id="serviceTableBody">
                                    <!-- AJAX will load data here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Add your additional section here -->
                {{-- <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Additional Information</h4>
                    </div>
                    <div class="card-body">
                        <!-- Your additional content here -->
                        <div id="additionalContent">
                            <!-- Content will be loaded here -->
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize date range picker with enhanced styling
    $('.daterange').daterangepicker({
        opens: 'left',
        autoUpdateInput: true,
        locale: {
            format: 'YYYY-MM-DD'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function(start, end, label) {
        $('#start_date').val(start.format('YYYY-MM-DD'));
        $('#end_date').val(end.format('YYYY-MM-DD'));
        loadReportData();
    });

    // Handle vehicle type change
    $('#vehicle_type').on('change', function() {
        const selectedType = $(this).val();
        filterVehiclesByType(selectedType);
        loadReportData();
    });

    // Filter vehicles based on selected type
    function filterVehiclesByType(type) {
        const $vehicleSelect = $('#vehicle_id');
        $vehicleSelect.empty().append('<option value="">All Vehicles</option>');
        
        if (type === '') {
            // If no type selected, show all vehicles
            @foreach($vehicles as $vehicle)
                $vehicleSelect.append($('<option>', {
                    value: {{ $vehicle->id }},
                    text: '{{ $vehicle->license_plate }}'
                }));
            @endforeach
        } else {
            // Filter vehicles by type
            @foreach($vehicles as $vehicle)
                if ('{{ $vehicle->owner_type ?? "" }}' === type || type === '') {
                    $vehicleSelect.append($('<option>', {
                        value: {{ $vehicle->id }},
                        text: '{{ $vehicle->license_plate }}'
                    }));
                }
            @endforeach
        }
        
        // Refresh select2
        $vehicleSelect.trigger('change');
    }

    // Load initial data
    loadReportData();

    // Form submission handler
    $('#reportFilterForm').on('submit', function(e) {
        e.preventDefault();
        loadReportData();
    });

    // Vehicle ID change handler
    $('#vehicle_id').on('change', function() {
        loadReportData();
    });

    function loadReportData() {
        const formData = $('#reportFilterForm').serialize();
        
        $.ajax({
            url: "{{ route('admin.vehicle.reports.data') }}",
            type: "GET",
            data: formData,
            success: function(response) {
                console.log(response);
                
                // Update summary cards
                $('#totalVehicles').text(response.summary.total_vehicles);
                $('#totalServiceCost').text(response.summary.total_service_cost.toLocaleString() + ' ৳');
                $('#totalFuelQty').text(response.summary.total_fuel_qty + ' Ltr');
                $('#totalFuelCost').text(response.summary.total_fuel_cost.toLocaleString() + ' ৳');

                // Update service table
                let tableHtml = '';
                response.services.forEach(service => {
                    tableHtml += `
                    <tr>
                        <td class="fw-bold">${service.vehicle.license_plate}</td>
                        <td>${service.service_count}</td>
                        <td>${service.total_cost.toLocaleString()} ৳</td>
                    </tr>`;
                });
                $('#serviceTableBody').html(tableHtml);
            }
        });
    }
});
</script>
@endpush