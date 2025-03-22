@forelse ($racks as $rack)
    <tr>
        <td>
            {{ $loop->iteration + $racks->firstItem() - 1 }}
        </td>
        <td>{{ $rack->name }}</td>
        <td>{{ $rack->total_products_count }}</td>
        <td class="action-table-data">
            <div class="edit-delete-action">
                <a class="me-2 edit-icon  p-2" href="#" data-bs-toggle="modal"
                    data-bs-target="#drawers-{{ $rack->id }}">
                        <i data-feather="eye" class="feather-eye"></i>
                </a>
                <a class="me-2 p-2" href="#" data-bs-toggle="modal" data-bs-target="#edit-rack-{{ $rack->id }}">
                    <i data-feather="edit" class="feather-edit"></i>
                </a>
                <form action="{{ route('admin.racks.destroy', $rack->id) }}" method="post" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <a class="confirm-text2 p-2" href="javascript:void(0);">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </form>
            </div>

        </td>
    </tr>

    <!-- Edit Rack -->
    <div class="modal fade" id="edit-rack-{{ $rack->id }}">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Edit Rack</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.racks.update', $rack->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Name*</label>
                                    <input type="text" class="form-control" value="{{ $rack->name }}"
                                        name="name">
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
    <!-- Edit Rack -->
@empty
    <tr class="text-center">
        <td colspan="7">No Brand Found</td>
    </tr>
@endforelse
