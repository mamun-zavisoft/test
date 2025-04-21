<?php $page = 'index'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash1.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>৳<span class="counters" data-count="{{ $totalDueAmount }}">{{ $totalDueAmount }}</span></h5>
                            <h6>Total Purchase Due</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash1 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash2.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>৳<span class="counters" data-count="{{ $sales->due_amount }}">{{ $sales->due_amount }}</span></h5>
                            <h6>Total Sales Due</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash2 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash3.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>৳<span class="counters" data-count="{{ $sales->grand_total }}">{{ $sales->grand_total }}</span></h5>
                            <h6>Total Sale Amount</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash3 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash4.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>৳<span class="counters" data-count="{{ $totalPurchaseAmount }}">{{ $totalPurchaseAmount }}</span></h5>
                            <h6>Total Expense Amount</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <a href="{{ url('vehicles') }}" class="dash-count text-decoration-none w-100 vehicle"
                            style="text-decoration: none; color: inherit;">
                            <div class="dash-counts text-start">
                                <h5>Vehicle</h5>
                                <h4 class="mb-2">{{ $totalVehicle }}</h4>
                                <div class="d-flex justify-content-center align-items-center mt-2 text-white">
                                    <span class="me-3">Self: {{ $selfVehicle }}</span>
                                    <div style="width: 2px; height: 20px; background: white; margin: 0 10px;"></div>
                                    <span class="ms-3">External: {{ $outsideVehicle }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <a href="{{ url('suppliers') }}" class="dash-count das1 text-decoration-none w-100">
                            <div class="dash-counts mb-3">
                                <h5>Supplier</h5>
                                <h4>{{ $totalSupplier }}</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <a href="{{ url('services') }}" class="dash-count das2 text-decoration-none w-100">
                            <div class="dash-counts mb-3">
                                <h5>Service</h5>
                                <h4>{{ $totalService }}</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <a href="{{ url('sales') }}" class="dash-count das3 text-decoration-none w-100">
                            <div class="dash-counts mb-3">
                                <h5>Sale Invoice</h5>
                                <h4>{{ $sales->count() }}</h4>
                            </div>
                        </a>
                    </div>
                </div>

            <!-- Button trigger modal -->
            <div class="row">
                <div class="col-xl-7 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Purchase & Sales <span id="selected-year">{{ $year }}</span></h5>
                            <div class="graph-sets">
                                <ul class="mb-0">
                                    <li>
                                        <span>Purchase</span>
                                    </li>
                                    <li>
                                        <span>Sales</span>
                                    </li>
                                </ul>
                                <div class="dropdown dropdown-wraper">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $year }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="year-dropdown">
                                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item" data-year="{{ $y }}">{{ $y }}</a>
                                            </li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- <div id="sales_charts"></div> --}}
                            <canvas id="chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill default-cover mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Recent Purchase</h4>
                            <div class="view-all-link">
                                <a href="{{ route('admin.purchases.index') }}" class="view-all d-flex align-items-center">
                                    View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                            class="feather-16"></i></span>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dataview">
                                <table class="table dashboard-recent-products">
                                    <thead>
                                        <tr>
                                            <th>Parts</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($purchases as $purchase)
                                        @foreach ($purchase->purchaseDetails as $data)
                                            <tr>
                                                <td>
                                                    <div class="productimgname">
                                                        <a href="javascript:void(0);" class="product-img stock-img">
                                                            <img src="{{ $data->product->thumbnail ?: asset('build/img/no-image.svg') }}"
                                                                alt="product" height="50px" width="30px">
                                                        </a>
                                                        <a href="javascript:void(0);">{{ $data->product->name }}</a>
                                                    </div>
                                                </td>
                                                <td>{{ $data->product?->purchase_price }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Service</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive dataview">
                        <table class="table dashboard-expired-products">
                            <thead>
                                <tr>
                                    <th>Service Type</th>
                                    <th>Total Price</th>
                                    <th>Given Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <td>
                                            <a href="#service-{{ $service->id }}" data-bs-toggle="modal" style="cursor: pointer; text-decoration: none;" class="service-name">
                                            {{ $service->service_type == 'self' ? 'Self (SteadFast)' : 'External' }}
                                        </td>
                                        <td>{{ number_format($service->grand_total) }}</td>
                                        <td>{{ $service->created_at?->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                                @foreach ($services as $service)
                                <div class="modal fade" id="service-{{ $service->id }}">
                                    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%; width: 1400px;">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                <div class="page-title">
                                                    <h4>Service Details</h4>
                                                </div>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body custom-modal-body" style="max-height: 90vh; overflow-y: auto;">
                                                <!-- Top Section: Vehicle and Invoice Info -->
                                                <div class="row mb-4">
                                                    <div class="col-md-8">
                                                        <div class="card h-100">
                                                            <div class="card-body ms-4">
                                                                <h5 class="card-title fw-bold mb-3">Vehicle Info</h5>
                                                                <div class="row mb-2">
                                                                    <!-- <div class="col-md-4 fw-bold">Vehicle Type:</div>
                                                                    <div class="col-md-8">
                                                                    
                                                                    </div> -->
                                                                    <p class="fw-bold">Vehicle Type:
                                                                        <span
                                                                            class="ps-2 text-{{ $service->service_type == 'self' ? 'success' : 'warning' }}">
                                                                            {{ $service->service_type == 'self' ? 'Self' : 'External' }}
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <!-- <div class="col-md-4 fw-bold">Vehicle Number:</div>
                                                                    <div class="col-md-8"><span
                                                                            class="copyable">{{ $service->vehicle?->license_plate ?? 'N/A' }}</span>
                                                                    </div> -->
                                                                    <p class="fw-bold">Vehicle Number: <span class="copyable ps-2">{{ $service->vehicle?->license_plate ?? 'N/A' }} </span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card h-100">
                                                            <div class="card-body">
                                                                <h5 class="card-title fw-bold mb-3">Service Info</h5>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Invoice NO:</div>
                                                                    <div class="col-md-7"><span
                                                                            class="copyable">{{ $service->transaction_id }}</span></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Service Type:</div>
                                                                    <div class="col-md-7">
                                                                        <span
                                                                            class="text-{{ $service->service_type == 'self' ? 'success' : 'warning' }}">
                                                                            {{ $service->service_type == 'self' ? 'Self' : 'External' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Payment Type:</div>
                                                                    <div class="col-md-7">{{ $service->payment_type_id ?? 'N/A' }}</div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Payment Status:</div>
                                                                    <div class="col-md-7">
                                                                        <span
                                                                            class="badge bg-{{ $service->paid_status == 'full_paid' ? 'success' : 'warning' }}">
                                                                            {{ ucwords(str_replace('_', ' ', $service->paid_status ?? 'N/A')) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-5 fw-bold">Service Date:</div>
                                                                    <div class="col-md-7">
                                                                        {{ $service->created_at?->format('d M Y') ?? 'N/A' }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Service Details Section -->
                                                <div class="card mb-4">
                                                    <div class="card-header bg-light">
                                                        <h5 class="card-title fw-bold m-0">Service Details</h5>
                                                    </div>
                                                    <div class="card-body ms-2">
                                                        <!-- Table Head -->
                                                        <div class="d-flex fw-bold border-bottom pb-2">
                                                            <div class="p-2" style="flex: 1;">Service Name</div>
                                                            <div class="p-2" style="flex: 1;">Unit Price</div>
                                                            <div class="p-2" style="flex: 1;">Code</div>
                                                        </div>

                                                        <!-- Table Body -->
                                                        @foreach ($service->serviceDetails as $data)
                                                            <div class="d-flex border-bottom py-2">
                                                                <div class="p-2" style="flex: 1;">{{ $data->serviceChart?->name }}</div>
                                                                <div class="p-2" style="flex: 1;">{{ number_format($data->price) }}
                                                                </div>
                                                                <div class="p-2" style="flex: 1;">{{ $data->serviceChart?->code }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Products Used Section -->
                                                @if ($service->sale)
                                                    <div class="card mb-4">
                                                        <div class="card-header bg-light">
                                                            <h5 class="card-title fw-bold m-0">Products Used</h5>
                                                        </div>
                                                        <div class="card-body ms-2">
                                                            <!-- Table Head -->
                                                            <div class="d-flex fw-bold border-bottom pb-2">
                                                                <div class="p-2" style="flex: 2;">Product Name</div>
                                                                <div class="p-2" style="flex: 1;">Price</div>
                                                                <div class="p-2" style="flex: 1;">Quantity</div>
                                                                <div class="p-2" style="flex: 1;">Total Price</div>
                                                            </div>

                                                            <!-- Table Body -->
                                                            @foreach ($service->sale?->saleDetails as $saleDetail)
                                                                <div class="d-flex border-bottom py-2">
                                                                    <div class="p-2" style="flex: 2;">
                                                                        {{ $saleDetail?->product?->name }}</div>
                                                                    <div class="p-2" style="flex: 1;">
                                                                        {{ number_format($saleDetail?->unit_price) }}</div>
                                                                    <div class="p-2" style="flex: 1;">{{ $saleDetail?->qty }}</div>
                                                                    <div class="p-2" style="flex: 1;">
                                                                        {{ number_format($saleDetail?->qty * $saleDetail->unit_price) }}
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Billing Summary Section -->
                                                <div class="card mb-4">
                                                    <div class="card-header bg-light">
                                                        <h5 class="card-title fw-bold m-0">Billing Summary</h5>
                                                    </div>
                                                    <div class="card-body mt-4">
                                                        <div class="row justify-content-end">
                                                            <div class="col-md-3">
                                                                <div class="border rounded p-3 bg-light">
                                                                    <div class="mb-2">
                                                                        <div class="fw-bold d-flex justify-content-between">
                                                                            <span>Service Total:</span>
                                                                            <span>{{ number_format($service->total_amount) }}.00</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <div class="fw-bold d-flex justify-content-between">
                                                                            <span>Discount:</span>
                                                                            <span>{{ number_format($service->discount_amount) }}.00</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <div class="fw-bold d-flex justify-content-between">
                                                                            <span>Grand Total:</span>
                                                                            <span>{{ number_format($service->grand_total) }}.00</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <div class="fw-bold d-flex justify-content-between">
                                                                            <span>Paid:</span>
                                                                            <span>{{ number_format($service->paid_amount) }}.00</span>
                                                                        </div>
                                                                    </div><hr>
                                                                    <div class="fw-bold fs-5 d-flex justify-content-between">
                                                                        <span>Due:</span>
                                                                        <span>{{ number_format($service->due_amount) }}.00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Additional Notes Section -->
                                                @if (!empty($service->notes))
                                                    <div class="card mb-4">
                                                        <div class="card-header bg-light">
                                                            <h5 class="card-title fw-bold m-0">Additional Notes</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="mb-0">{{ $service->notes }}</p>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div id="print-invoice-template-{{ $service->id }}" style="display: none;">
                                                    @include('backend.services._service_invoice_print')
                                                </div>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer justify-content-end">
                                                <button type="button" class="btn btn-secondary me-2"
                                                    onclick="printInvoice(this, {{ $service->id }})">
                                                    <i class="fas fa-print me-1"></i> Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Pass the chart data from Blade to JavaScript
        var chartData = @json($chartData);

        // Initialize the chart with the data
        renderChart(chartData);

        $('#year-dropdown').on('click', 'a', function() {
            var year = $(this).data('year');
            $('#selected-year').text(year);
            
            fetchSalePurchaseData(year);
        });

        function fetchSalePurchaseData(year) {
            $.ajax({
                url: '/',
                method: 'GET',
                data: { year: year }, 
                dataType: 'json', // Explicitly request JSON
                success: function(response) {
                    if (response && response.chartData) {
                        renderChart(response.chartData);
                    } else {
                        console.error("Invalid response format:", response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        // Function to render the chart
        function renderChart(data) {
            // console.log(data);
            
            var ctx = document.getElementById('chart').getContext('2d');
            if (window.myChart) {
                window.myChart.destroy();
            }
            window.myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.month), 
                    datasets: [{
                        label: 'Sales',
                        data: data.map(item => item.sales),
                        backgroundColor: 'rgba(243, 21, 21, 0.98)',
                        borderColor: 'rgb(31, 104, 104)',
                        borderWidth: 1,
                        // borderRadius: 8 
                        
                    },
                    {
                        label: 'Purchase',
                        data: data.map(item => item.purchases),
                        backgroundColor: 'rgb(13, 230, 78)',
                        borderColor: 'rgb(90, 25, 219)',
                        borderWidth: 1,
                        // borderRadius: 8 
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
    
