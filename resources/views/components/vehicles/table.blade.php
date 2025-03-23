<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Owner Type</th>
            <th>Vehicle Number</th>
            <th>Zone</th>
            <th>Status</th>
            <th>Created On</th>
            <th class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($entity as $vehicle)
            <tr>
                <td>
                    {{ $loop->iteration + $entity->firstItem() - 1 }}
                </td>
                <td>{{ $vehicle->owner_type == 1 ? 'self' : 'external' }}</td>
                <td>{{ $vehicle->license_plate }}</td>
                <td>{{ $vehicle->zone?->name }}</td>
                <td><span
                        class="badge rounded-pill bg-outline-{{ $vehicle->status == 1 ? 'success' : 'warning' }}">{{ $vehicle->status == 1 ? 'Active' : 'In Service' }}</span>
                </td>
                <td>{{ $vehicle->created_at?->format('d M Y') }}</td>
                <td class="action-table-data">
                    <div class="edit-delete-action">
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
                <div class="modal-dialog modal-dialog-centered custom-modal-two">
                    <div class="modal-content">
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
                                    <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Owner Type*</label>
                                            <select class="select" name="owner_type">
                                                <option value="">Choose</option>
                                                <option value="1"
                                                    {{ $vehicle->owner_type == '1' ? 'selected' : '' }}>Self</option>
                                                <option value="2"
                                                    {{ $vehicle->owner_type == '2' ? 'selected' : '' }}>External
                                                </option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">License Plate*</label>
                                            <input type="text" class="form-control"
                                                value="{{ $vehicle->license_plate }}" name="license_plate">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status*</label>
                                            <select class="select" name="status">
                                                <option value="">Choose</option>
                                                <option value="1" {{ $vehicle->status == '1' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="2" {{ $vehicle->status == '2' ? 'selected' : '' }}>
                                                    In Service</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer-btn">
                                            <button type="button" class="btn btn-cancel me-2"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-submit">Save
                                                Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <tr class="text-center">
                <td colspan="7">No Vehicle Found</td>
            </tr>
        @endforelse

    </tbody>
</table>
<x-pagination :paginator="$entity" />
