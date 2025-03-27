<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Reg.no</th>
            <th>Model</th>
            <th>Vehicle Type</th>
            <th>Reg.Date</th>
            <th>Reg.Validity</th>
            <th>Status</th>
            <th>Created On</th>
            <th class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($vehicles as $vehicle)
            <tr>
                <td>
                    {{ $loop->iteration + $vehicles->firstItem() - 1 }}
                </td>
                <td>{{ $vehicle->license_plate }}</td>
                <td>{{ $vehicle->vehicleModel?->name }}</td>
                <td>
                    @if ($vehicle->vehicle_type == 1)
                        Covered Van
                    @elseif ($vehicle->vehicle_type == 2)
                        MotorBike
                    @elseif ($vehicle->vehicle_type == 3)
                        Pickup
                    @elseif ($vehicle->vehicle_type == 4)
                        Truck
                    @elseif ($vehicle->vehicle_type == 5)
                        TBA/Other
                    @endif
                </td>
                <td>{{ $vehicle->registration_date }}</td>
                <td>{{ $vehicle->registration_validity }}</td>
                
                <td><span
                        class="badge rounded-pill bg-outline-{{ $vehicle->status == 1 ? 'success' : 'warning' }}">{{ $vehicle->status == 1 ? 'Active' : 'In Service' }}</span>
                </td>
                <td>{{ $vehicle->created_at?->format('d M Y') }}</td>
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="me-2 p-2" href="javascript:void(0);" data-bs-toggle="modal"
                            data-bs-target="#vehicle-{{ $vehicle->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye action-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </a>
                        <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#edit-vehicle-{{ $vehicle->id }}">
                            <i data-feather="edit" class="feather-edit"></i>
                        </a>
                        <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="post"
                            class="delete-form">
                            @csrf
                            @method('DELETE')
                            <a class="confirm-text2 p-2" href="javascript:void(0);">
                                <i data-feather="trash-2" class="feather-trash-2"></i>
                            </a>
                        </form>
                    </div>

                </td>
            </tr>
            <!-- Edit Vehicle -->
            <div class="modal fade" id="edit-vehicle-{{ $vehicle->id }}">
                <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 95%; width: 1400px; max-height: 95vh; height: 90vh;">
                    <div class="modal-content" style="height: 100%;">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Edit Vehicle</h4>
                                    </div>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body new-employee-field">
                                    <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" class="editForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Owner Type*</label>
                                                <select class="select form-control" name="owner_type">
                                                    <option value="">Choose</option>
                                                    <option value="1"{{ $vehicle->owner_type == 1 ? 'selected' : '' }}>Self</option>
                                                    <option value="2"{{ $vehicle->owner_type == 2 ? 'selected' : '' }}>External</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Register Number*</label>
                                                <input type="text" name="license_plate" value="{{ $vehicle->license_plate }}" class="form-control">
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Status*</label>
                                                <select class="select form-control" name="status">
                                                    <option value="">Choose</option>
                                                    <option value="1"{{ $vehicle->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="2"{{ $vehicle->status == 2 ? 'selected' : '' }}>In Service</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Select Hub*</label>
                                                <select class="select form-control" name="hub_id">
                                                    <option value="">Choose</option>
                                                    @foreach ($hubs as $hub)
                                                    <option value="{{$hub->id}}"{{ $hub->id == $vehicle->hub_id ? 'selected' : '' }}>{{ $hub->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Vehicle Type*</label>
                                                <select class="select form-control" name="vehicle_type">
                                                    <option value="">Choose</option>
                                                    <option value="1"{{ $vehicle->vehicle_type == 1 ? 'selected' : '' }}>Covered Van</option>
                                                    <option value="2"{{ $vehicle->vehicle_type == 2 ? 'selected' : '' }}>Motor Bike</option>
                                                    <option value="3"{{ $vehicle->vehicle_type == 3 ? 'selected' : '' }}>Pick Up</option>
                                                    <option value="4"{{ $vehicle->vehicle_type == 4 ? 'selected' : '' }}>Truck</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Select Model*</label>
                                                <select class="select form-control" name="vehicle_model_id">
                                                    <option value="">Choose</option>
                                                    @foreach ($vehicleModels as $vehicleModel)
                                                    <option value="{{ $vehicleModel->id }}"{{ $vehicleModel->id == $vehicle->vehicle_model_id ? 'selected' : '' }}>{{ $vehicleModel->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">ODO(current odometer)</label>
                                                <input type="number" name="current_odometer" value="{{ $vehicle->current_odometer }}" class="form-control">
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Registration Date*</label>
                                                <input type="date" name="registration_date" value="{{ $vehicle->registration_date }}" class="form-control">
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Registration Validity*</label>
                                                <input type="date" name="registration_validity" value="{{ $vehicle->registration_validity }}" class="form-control">
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Tax Token Validity*</label>
                                                <input type="date" name="tax_token_validity" value="{{ $vehicle->tax_token_validity }}" class="form-control">
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Fitness Validity*</label>
                                                <input type="date" name="fitness_validity" value="{{ $vehicle->fitness_validity }}" class="form-control">
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Road Permit Validity*</label>
                                                <input type="date" name="road_permit_validity" value="{{ $vehicle->road_permit_validity }}" class="form-control">
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label">Insurance Validity*</label>
                                                <input type="date" name="insurance_validity" value="{{ $vehicle->insurance_validity }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer-btn">
                                            <button type="button" class="btn btn-cancel me-2"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Vehicles Details -->
            <div class="modal fade" id="vehicle-{{ $vehicle->id }}">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%; width: 1400px;">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Vehicle Details</h4>
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
                                                <div class="col-md-4 fw-bold">Vehicle Type:</div>
                                                <div class="col-md-8">
                                                {{ 
                                                    $vehicle->owner_type == 1 ? 'Covered Van' : 
                                                    ($vehicle->vehicle_type == 2 ? 'MotorBike' : 
                                                    ($vehicle->vehicle_type == 3 ? 'Pickup' : 
                                                    ($vehicle->vehicle_type == 4 ? 'Truck' : 'Unknown')))
                                                }}
                                                </div>                                     
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-4 fw-bold">Registration Number:</div>
                                                <div class="col-md-8">{{ $vehicle->license_plate ?? 'N/A' }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-4 fw-bold">Vehicle Model:</div>
                                                <div class="col-md-8">{{ $vehicle->vehicleModel?->name ?? 'N/A' }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-4 fw-bold">Hub:</div>
                                                <div class="col-md-8">{{ $vehicle->hub?->name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <!-- Service Details Section -->
                        <div class="card mb-5">
                            <div class="card-header bg-light">
                                <h5 class="card-title fw-bold m-0">Vehicle Details</h5>
                            </div>
                            <div class="card-body ms-2">
                                <!-- Table Head -->
                                <div class="d-flex fw-bold border-bottom pb-2">
                                    <div class="p-2" style="flex: 1;">Reg.No</div>
                                    <div class="p-2" style="flex: 1;">Current ODOMeter</div>
                                    <div class="p-2" style="flex: 1;">Tax.T.Validity</div>
                                    <div class="p-2" style="flex: 1;">Fitness Validity</div>
                                    <div class="p-2" style="flex: 1;">Road Permit Validity</div>
                                    <div class="p-2" style="flex: 1;">Insurance Validity</div>
                                </div>

                                <!-- Table Body -->
                                <div class="d-flex border-bottom py-2">
                                    <div class="p-2" style="flex: 1;">{{ $vehicle->license_plate }}</div>
                                    <div class="p-2" style="flex: 1;">{{ $vehicle->current_odometer }} km</div>
                                    <div class="p-2" style="flex: 1;">{{ $vehicle->tax_token_validity }}</div>
                                    <div class="p-2" style="flex: 1;">{{ $vehicle->fitness_validity }}</div>
                                    <div class="p-2" style="flex: 1;">{{ $vehicle->road_permit_validity }}</div>
                                    <div class="p-2" style="flex: 1;">{{ $vehicle->insurance_validity }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

        @empty
            <tr class="text-center">
                <td colspan="9">No Vehicle Found</td>
            </tr>
        @endforelse

    </tbody>
</table>
<x-pagination :paginator="$vehicles" />
