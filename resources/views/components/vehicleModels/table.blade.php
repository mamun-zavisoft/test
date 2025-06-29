<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Name</th>
            <th>Manufacturer</th>
            <th>Engine CC</th>
            <th>Fuel Capacity</th>
            <th>Payload Capacity</th>
            <th>Average Mileage</th>
            <th>Body Length</th>
            @permission(['vehicle-model-update', 'vehicle-model-delete'])
            <th class="no-sort">Action</th>
            @endpermission
        </tr>
    </thead>
    <tbody id="tbody">
        @forelse($vehicleModels as $vehicleModel)
            <tr>
                <td>{{ $loop->iteration + $vehicleModels->firstItem() - 1 }}</td>
                <td>{{ $vehicleModel->name }}</td>
                <td>{{ $vehicleModel->manufacturer }}</td>
                <td>{{ $vehicleModel->engine_cc }} CC</td>
                <td>{{ $vehicleModel->fuel_capacity }} Ltr</td>
                <td>{{ $vehicleModel->payload_capacity }} KG</td>
                <td>{{ $vehicleModel->avg_mileage }} KM/L</td>
                <td>{{ $vehicleModel->body_length }} Feet</td>
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        <!-- <a class="me-2 edit-icon p-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#vehicleModels-{{ $vehicleModel->id }}">
                            <i data-feather="eye" class="feather-eye"></i>
                        </a> -->
                        @permission('vehicle-model-update')
                        <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#edit-vehicleModel-{{ $vehicleModel->id }}">
                            <i data-feather="edit" class="feather-edit"></i>
                        </a>
                        @endpermission
                        @permission('vehicle-model-delete')
                        <form action="{{ route('admin.vehicle-models.destroy', $vehicleModel->id) }}"
                            method="post" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <a class="confirm-text2 p-2" href="javascript:void(0);">
                                <i data-feather="trash-2" class="feather-trash-2"></i>
                            </a>
                        </form>
                        @endpermission
                    </div>
                </td>
            </tr>

            <!-- Edit Vehicle Model -->
            <div class="modal fade" id="edit-vehicleModel-{{ $vehicleModel->id }}">
                <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 50%;">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div
                                    class="modal-header border-0 custom-modal-header justify-content-between">
                                    <div class="page-title">
                                        <h4>Edit Vehicle Model</h4>
                                    </div>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body new-employee-field">
                                    <form class="editForm" data-id="{{ $vehicleModel->id }}"
                                        action="{{ route('admin.vehicle-models.update', $vehicleModel->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Name*</label>
                                            <input type="text" class="form-control"
                                            value="{{ $vehicleModel->name }}" name="name">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Manufacturer*(Brand)</label>
                                            <input type="text" class="form-control"
                                                value="{{ $vehicleModel->manufacturer }}" name="manufacturer">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Engine CC*</label>
                                            <input type="number" class="form-control" step="0.01"
                                            value="{{ $vehicleModel->engine_cc }}" name="engine_cc">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fuel Capacity*(Liter)</label>
                                            <input type="number" class="form-control" step="0.01"
                                            value="{{ $vehicleModel->fuel_capacity }}" name="fuel_capacity">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Payload Capacity*(KG)</label>
                                            <input type="number" class="form-control" step="0.01"
                                            value="{{ $vehicleModel->payload_capacity }}" name="payload_capacity">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Average Mileage(KM/L)</label>
                                            <input type="number" class="form-control" step="0.01"
                                            value="{{ $vehicleModel->avg_mileage }}" name="avg_mileage">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Body Length(Feet)</label>
                                            <input type="number" class="form-control" step="0.01"
                                            value="{{ $vehicleModel->body_length }}" name="body_length">
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
            <!-- Edit Vehicle Model -->
        @empty
            <tr class="text-center">
            <td colspan="7">No Vehicle Model Found</td>
        </tr>
        @endforelse

    </tbody>
</table>
<x-pagination :paginator="$vehicleModels" />