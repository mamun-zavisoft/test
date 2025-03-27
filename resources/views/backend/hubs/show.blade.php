@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Hub Detail" button="Go Back" back-button-route="admin.hubs.index" />
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">Hub Information</h5>
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <strong>Hub Name:</strong>
                                            <span>{{ $hub->name }}</span>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <strong>Hub ID:</strong>
                                            <span>{{ $hub->custom_hub_id ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <strong>Address:</strong>
                                            <span>{{ $hub->address ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <strong>Phone:</strong>
                                            <span>{{ $hub->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold m-0">Vehicles in this Hub</h5>
                    <div class="badge bg-info fs-6">
                        Total Vehicles: {{ $hub->vehicles->count() }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="dataTable">
                        {{-- <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Registration Number</th>
                                    <th>Vehicle Type</th>
                                    <th>Owner Type</th>
                                    <th>Current ODO</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hub->vehicles()->paginate(request('per_page', 10)) as $vehicle)
                                    <tr>
                                        <td>{{ $loop->iteration + $hub->vehicles()->paginate(request('per_page', 10))->firstItem() - 1 }}</td>
                                        <td>{{ $vehicle->license_plate }}</td>
                                        <td>
                                            {{ $vehicle->owner_type == 1
                                                ? 'Covered Van'
                                                : ($vehicle->vehicle_type == 2
                                                    ? 'MotorBike'
                                                    : ($vehicle->vehicle_type == 3
                                                        ? 'Pickup'
                                                        : ($vehicle->vehicle_type == 4
                                                            ? 'Truck'
                                                            : 'Unknown'))) }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge text-{{ $vehicle->owner_type == 1 ? 'success' : 'warning' }}">
                                                {{ $vehicle->owner_type == 1 ? 'Self' : 'External' }}
                                            </span>
                                        </td>
                                        <td>{{ $vehicle->current_odometer }} km</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 edit-icon p-2" data-bs-toggle="modal"
                                                    data-bs-target="#vehicle-{{ $vehicle->id }}">
                                                    <i data-feather="eye" class="feather-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No vehicles assigned to this hub
                                        </td>
                                    </tr>
                                @endforelse
                                
                            </tbody>
                        </table>
                    
                        <x-pagination :paginator="$hub->vehicles()->paginate(request('per_page', 10))" /> --}}
                    <x-vehicles.tableForHub :vehicles="$vehicles" :hubs="$all_hubs" :vehicleModels="$vehicleModels"/>

                    </div>
                </div>
            </div>

            <!-- Vehicle Detail Modals -->
            @foreach ($hub->vehicles as $vehicle)
                <div class="modal fade" id="vehicle-{{ $vehicle->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title ">Vehicle Details - <span class="text-primary fw-bold">{{ $vehicle->license_plate }}</span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold mb-3">Vehicle Information</h5>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Owner Type:</strong>
                                                        <span
                                                            class="badge text-{{ $vehicle->owner_type == 1 ? 'success' : 'warning' }} ms-2">
                                                            {{ $vehicle->owner_type == 1 ? 'Self' : 'External' }}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Vehicle Type:</strong>
                                                        <span class="ms-2">
                                                            {{ $vehicle->owner_type == 1
                                                                ? 'Covered Van'
                                                                : ($vehicle->vehicle_type == 2
                                                                    ? 'MotorBike'
                                                                    : ($vehicle->vehicle_type == 3
                                                                        ? 'Pickup'
                                                                        : ($vehicle->vehicle_type == 4
                                                                            ? 'Truck'
                                                                            : 'Unknown'))) }}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Registration Number:</strong>
                                                        <span class="ms-2">{{ $vehicle->license_plate ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Vehicle Model:</strong>
                                                        <span
                                                            class="ms-2">{{ $vehicle->vehicleModel?->name ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Current ODO:</strong>
                                                        <span class="ms-2">{{ $vehicle->current_odometer }} km</span>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Hub:</strong>
                                                        <span class="ms-2">{{ $vehicle->hub?->name ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title fw-bold m-0">Validity Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <strong>Tax Token Validity:</strong>
                                                <span class="ms-2">{{ $vehicle->tax_token_validity ?? 'N/A' }}</span>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <strong>Fitness Validity:</strong>
                                                <span class="ms-2">{{ $vehicle->fitness_validity ?? 'N/A' }}</span>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <strong>Road Permit Validity:</strong>
                                                <span class="ms-2">{{ $vehicle->road_permit_validity ?? 'N/A' }}</span>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <strong>Insurance Validity:</strong>
                                                <span class="ms-2">{{ $vehicle->insurance_validity ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
