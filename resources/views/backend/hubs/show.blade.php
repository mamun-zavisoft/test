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

            @endforeach
        </div>
    </div>
@endsection
