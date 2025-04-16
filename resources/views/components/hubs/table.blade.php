<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Name</th>
            <!-- <th>Zone</th> -->
            <th>Hub Id</th>
            <th>Phone</th>
            <th>Address</th>
            @permission(['hub-show', 'hub-update', 'hub-delete'])
            <th class="no-sort">Action</th>
            @endpermission
        </tr>
    </thead>
    <tbody id="tbody">
        @forelse($hubs as $hub)
            <tr>
                <td>{{ $loop->iteration + $hubs->firstItem() - 1 }}</td>
                <td>{{ $hub->name }}</td>
                <!-- <td>{{ $hub->zone?->name }}</td> -->
                <td>{{ $hub->custom_hub_id }}</td>
                <td>{{ $hub->phone }}</td>
                <td>{{ $hub->address }}</td>
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        @permission('hub-show')
                        <a class="me-2 edit-icon p-2" href="{{ route('admin.hubs.show', $hub->id) }}" >
                            <i data-feather="eye" class="feather-eye"></i>
                        </a>
                        @endpermission
                        @permission('hub-update')
                        <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#edit-hub-{{ $hub->id }}">
                            <i data-feather="edit" class="feather-edit"></i>
                        </a>
                        @endpermission
                        @permission('hub-delete')
                        <form action="{{ route('admin.hubs.destroy', $hub->id) }}"
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

            <!-- Edit hub -->
            <div class="modal fade" id="edit-hub-{{ $hub->id }}">
                <div class="modal-dialog modal-dialog-centered custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div
                                    class="modal-header border-0 custom-modal-header justify-content-between">
                                    <div class="page-title">
                                        <h4>Edit Hub</h4>
                                    </div>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body new-employee-field">
                                    <form class="editForm" data-id="{{ $hub->id }}"
                                        action="{{ route('admin.hubs.update', $hub->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Name*</label>
                                            <input type="text" class="form-control"
                                            value="{{ $hub->name }}" name="name">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Hub Id*</label>
                                            <input type="text" class="form-control"
                                                value="{{ $hub->custom_hub_id }}" name="custom_hub_id">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone*</label>
                                            <input type="text" class="form-control"
                                            value="{{ $hub->phone }}" name="phone">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control"
                                            value="{{ $hub->address }}" name="address">
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
            <!-- Edit hub -->
        @empty
            <tr class="text-center">
            <td colspan="7">No Hub Found</td>
        </tr>
        @endforelse

    </tbody>
</table>
<x-pagination :paginator="$hubs" />