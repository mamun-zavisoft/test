<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Owner Type</th>
            <th>Reg.no</th>
            <th>Model</th>
            <th>Vehicle Type</th>
            <th>Hub</th>
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
                <td>
                    <span class="badge rounded-pill {{ $vehicle->owner_type == 1 ? 'bg-primary' : 'bg-secondary' }}">
                        {{ $vehicle->owner_type == 1 ? 'Self' : 'External' }}
                    </span>
                </td>
                <td><span class="copyable">{{ $vehicle->license_plate }}</span></td>
                <td>{{ $vehicle->vehicleModel?->name ?? '-' }}</td>
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
                    @else
                        -
                    @endif
                </td>
                <td>{{ $vehicle->hub?->name ?? '-' }}</td>
                <td>{{ $vehicle->registration_date ?? '-' }}</td>
                <td>{{ $vehicle->registration_validity ?? '-' }}</td>
                
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
            <div class="modal fade" id="edit-vehicle-{{ $vehicle->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content border-0 rounded-4 shadow-lg" style="max-height: 100vh;">
                    <div class="modal-header bg-gradient text-white rounded-top-4 justify-content-between"
                        style="background: linear-gradient(90deg, #007bff, #0056b3);">
                        <div class="page-title">
                            <h5 class="modal-title fw-bold">Edit Vehicle </h5>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="$('#storeForm')[0].reset()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                        <div class="modal-body p-4">
                            <form method="POST" id="editForm" action="{{ route('admin.vehicles.update', $vehicle->id) }}" class="editForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="edit_vehicle_id">
                                <div class="accordion" id="editVehicleAccordion">
                                    <!-- Accordion 1: Basic Info -->
                                    <div class="accordion-item mb-3 rounded-3 overflow-hidden border border-1">
                                        <h2 class="accordion-header" id="editBasicInfoHeading">
                                            <button class="accordion-button fw-bold p-3" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#editBasicInfo" aria-expanded="true" aria-controls="editBasicInfo">
                                                Vehicle Basic Info
                                            </button>
                                        </h2>
                                        <div id="editBasicInfo" class="accordion-collapse collapse show" aria-labelledby="editBasicInfoHeading"
                                            data-bs-parent="#editVehicleAccordion">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Owner Type<span class="text-danger">*</span></label>
                                                        <select name="owner_type" id="edit_owner_type" class="form-select">
                                                            <option value="">Choose</option>
                                                            <option value="1"{{ $vehicle->owner_type == 1 ? 'selected' : '' }}>Self</option>
                                                            <option value="2"{{ $vehicle->owner_type == 2 ? 'selected' : '' }}>External</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Register Number<span class="text-danger">*</span></label>
                                                        <input type="text" name="license_plate" id="edit_license_plate" value="{{ $vehicle->license_plate }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Vehicle Type<span class="text-danger">*</span></label>
                                                        <select name="vehicle_type" id="edit_vehicle_type" class="form-select">
                                                            <option value="">Choose</option>
                                                            <option value="1" {{ $vehicle->vehicle_type == 1 ? 'selected' : '' }}>Covered Van</option>
                                                            <option value="2" {{ $vehicle->vehicle_type == 2 ? 'selected' : '' }}>Motor Bike</option>
                                                            <option value="3" {{ $vehicle->vehicle_type == 3 ? 'selected' : '' }}>Pick Up</option>
                                                            <option value="4" {{ $vehicle->vehicle_type == 4 ? 'selected' : '' }}>Truck</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">ODO (current odometer)<span class="text-danger">*</span></label>
                                                        <input type="number" name="current_odometer" value="{{ $vehicle->current_odometer }}" id="edit_current_odometer" class="form-control"
                                                            placeholder="Current Mileage" onwheel="this.blur()">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Select Hub</label>
                                                        <select name="hub_id" id="edit_hub_id" class="form-select">
                                                            <option value="">Choose</option>
                                                            @foreach ($hubs as $hub)
                                                            <option value="{{ $hub->id }}" {{ $hub->id == $vehicle->hub_id ? 'selected' : '' }}>{{ $hub->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Select Model</label>
                                                        <select name="vehicle_model_id" id="edit_vehicle_model_id" class="form-select">
                                                            <option value="">Choose</option>
                                                            @foreach ($vehicleModels as $model)
                                                            <option value="{{ $model->id }}" {{ $model->id == $vehicle->vehicle_model_id ? 'selected' : '' }}>{{ $model->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Status</label>
                                                        <select name="status" id="edit_status" class="form-select">
                                                            <option value="">Choose</option>
                                                            <option value="1" {{ $vehicle->status == 1 ? 'selected' : '' }}>Active</option>
                                                            <option value="2" {{ $vehicle->status == 2 ? 'selected' : '' }}>In Service</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Accordion 2: Date Info -->
                                    <div class="accordion-item rounded-3 overflow-hidden border border-1">
                                        <h2 class="accordion-header" id="editDateInfoHeading">
                                            <button class="accordion-button collapsed fw-bold p-3" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#editDateInfo" aria-expanded="false" aria-controls="editDateInfo">
                                                Vehicle Date Information
                                            </button>
                                        </h2>
                                        <div id="editDateInfo" class="accordion-collapse collapse" aria-labelledby="editDateInfoHeading"
                                            data-bs-parent="#editVehicleAccordion">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Registration Date</label>
                                                        <input type="date" name="registration_date" value="{{ $vehicle->registration_date }}" id="edit_registration_date" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Registration Validity</label>
                                                        <input type="date" name="registration_validity" value="{{ $vehicle->registration_validity }}" id="edit_registration_validity" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Tax Token Validity</label>
                                                        <input type="date" name="tax_token_validity" value="{{ $vehicle->tax_token_validity }}" id="edit_tax_token_validity" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Fitness Validity</label>
                                                        <input type="date" name="fitness_validity" value="{{ $vehicle->fitness_validity }}" id="edit_fitness_validity" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Road Permit Validity</label>
                                                        <input type="date" name="road_permit_validity" value="{{ $vehicle->road_permit_validity }}" id="edit_road_permit_validity" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Insurance Validity</label>
                                                        <input type="date" name="insurance_validity" value="{{ $vehicle->insurance_validity }}" id="edit_insurance_validity" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="modal-footer d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary py-1 px-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary py-1 px-2" id="edit_submit_btn">Update</button>
                                </div>
                            </form>
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
                                                <div class="col-md-4 fw-bold">Owner Type:</div>
                                                <div class="col-md-8">
                                                    <span class="text-{{ $vehicle->owner_type == 1 ? 'success' : 'warning' }}">
                                                        {{ $vehicle->owner_type == 1 ? 'Self' : 'External' }}
                                                    </span>
                                                </div>                                     
                                            </div>
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
                                    <div class="p-2" style="flex: 1;">ODO</div>
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
