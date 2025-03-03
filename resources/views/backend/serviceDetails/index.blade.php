<?php $page = 'serviceDetails-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                                </a>

                            </div>
                        </div>

                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>Newest</option>
                                <option>Oldest</option>
                            </select>
                        </div>
                    </div>
                    <!-- /Filter -->
                    
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table  datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Service Name</th>
                                    <th>Service Type</th>
                                    <th>Vehicle</th>
                                    <th>Price</th>
                                    <th>Grand Total</th>
                                    <th>Total Amount</th>
                                    <th>Created At</th>                               
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="sales-list">
                                @foreach ($serviceDetails as $serviceDetail)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{ $serviceDetail->serviceChart->name }}</td>
                                    <td>{{ $serviceDetail->service->service_type }}</td>
                                    <td>{{ $serviceDetail->service->vehicle?->license_plate }}</td>
                                    <td>{{ number_format($serviceDetail->serviceChart->price) }}</td>
                                    <td>{{ number_format($serviceDetail->service->grand_total) }}</td>
                                    <td>{{ number_format($serviceDetail->service->total_amount) }}</td>
                                    <td>{{ $serviceDetail->created_at?->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a class="action-set" href="javascript:void(0);" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#show-service-details-{{ $serviceDetail->id }}"><i data-feather="eye"
                                                        class="info-img"></i>Service Detail</a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <div class="modal fade" id="show-service-details-{{ $serviceDetail->id }}">
                                    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%; width: 90%; height: 100vh;">
                                        <div class="modal-content" style="height: 100%;">
                                            <div class="page-wrapper-new p-0" style="height: 100%;">
                                                <div class="content" style="height: 100%;">
                                                    <div class="modal-header border-0 custom-modal-header">
                                                        <div class="page-title">
                                                            <h4>Service Details</h4>
                                                        </div>
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body custom-modal-body new-employee-field" style="flex-grow: 1; overflow-y: auto;">
                                                        <div class="row mb-4">
                                                            <div class="col-md-8">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h5 class="card-title font-weight-bold mb-3">Vehicle Info</h5>
                                                                        <p class="mb-1">Owner Type: {{ $serviceDetail->service->vehicle?->owner_type == 1 ? 'Self' : 'External'}}</p>
                                                                        <p class="mb-1">License Plate: {{ $serviceDetail->service->vehicle?->license_plate}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h5 class="card-title font-weight-bold mb-3">Invoice Info</h5>
                                                                        <div class="d-flex justify-content-between mb-1">
                                                                            <span>Reference:</span>
                                                                            <span class="fw-bolder"></span>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-1">
                                                                            <span>Payment Status:</span>
                                                                            <span class="fw-bolder">
                                                                            </span>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between">
                                                                            <span>Status:</span>
                                                                            <span class="fw-bolder">
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Service Details Section -->
                                                        <div class="card mb-4">
                                                            <div class="card-body">
                                                                <h5 class="card-title font-weight-bold mb-4">Service Details</h5>
                                                                <div class="d-flex flex-wrap fw-bold border-bottom pb-2">
                                                                    <div class="flex-fill">Service Name</div>
                                                                    <div class="flex-fill text-center">Code</div>
                                                                    <div class="flex-fill text-center">Price</div>
                                                                    <div class="flex-fill text-center">Service Type</div>
                                                                    <div class="flex-fill text-center">Discount</div>
                                                                </div>

                                                                <div class="d-flex flex-wrap py-2 border-bottom">
                                                                    <div class="flex-fill">{{ $serviceDetail->serviceChart?->name }}</div>
                                                                    <div class="flex-fill text-center" style="margin-left: 50px;">{{ $serviceDetail->serviceChart?->code }}</div>
                                                                    <div class="flex-fill text-center" style="margin-left: 50px;">{{ $serviceDetail->serviceChart?->price }}</div>
                                                                    <div class="flex-fill text-center" style="margin-left: 50px;">{{ $serviceDetail->service->service_type }}</div>
                                                                    <div class="flex-fill text-center" style="margin-left: 50px;">{{ $serviceDetail->service->discount }}</div>
                                                                </div>
                                                                <!-- Total Section -->
                                                                <div class="row justify-content-end mt-4">
                                                                    <div class="col-md-5">
                                                                        <div class="bg-light p-3 rounded">                                                
                                                                            <div class="d-flex justify-content-between">
                                                                                <span class="font-weight-bold">Grand Total</span>
                                                                                <span class="font-weight-bold">{{ $serviceDetail->service->total_amount }}</span>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between mb-2">
                                                                                <span>Discount</span>
                                                                                <span>{{ $serviceDetail->service->discount }}</span>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between mb-2">
                                                                                <span>Total Price</span>
                                                                                <span>{{ $serviceDetail->service->grand_total }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Footer Actions -->
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-end">
                                                                    <button class="btn btn-secondary me-2">
                                                                        <i class="fa fa-print me-1"></i> Print
                                                                    </button>
                                                                    <button class="btn btn-primary">
                                                                        <i class="fa fa-download me-1"></i> Download PDF
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
