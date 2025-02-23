@foreach ($entity as $brand)
    <tr>
        <td>
            {{ $loop->iteration + $brands->firstItem() - 1 }}
        </td>
        <td>{{ $brand->name }}</td>
        <td><span class="d-flex"><img src="{{ URL::asset('/build/img/brand/brand-icon-01.png') }}" alt=""></span>
        </td>
        <td>{{ $brand->created_at->format('d M Y') }}</td>
        <td><span
                class="badge rounded-pill bg-outline-{{ $brand->status == 1 ? 'success' : 'warning' }}">{{ $brand->status == 1 ? 'Active' : 'Inactive' }}</span>
        </td>
        <td class="action-table-data">
            <div class="edit-delete-action">
                <a class="me-2 p-2" href="#" data-bs-toggle="modal" data-bs-target="#edit-brand-{{ $brand->id }}">
                    <i data-feather="edit" class="feather-edit"></i>
                </a>
                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="post" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <a class="confirm-text2 p-2" href="javascript:void(0);">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </form>
            </div>

        </td>
    </tr>

    <!-- Edit Brand -->
    <div class="modal fade" id="edit-brand-{{ $brand->id }}">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Edit Brand</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Brand</label>
                                    <input type="text" class="form-control" value="{{ $brand->name }}"
                                        name="name">
                                </div>
                                <label class="form-label">Logo</label>
                                <div class="profile-pic-upload mb-3">
                                    <div class="profile-pic brand-pic">
                                        <span><img src="{{ URL::asset('/build/img/brand/brand-icon-02.png') }}"
                                                alt=""></span>
                                        <a href="javascript:void(0);" class="remove-photo"><i data-feather="x"
                                                class="x-square-add"></i></a>
                                    </div>
                                    <div class="image-upload mb-0">
                                        <input type="file">
                                        <div class="image-uploads">
                                            <h4>Change Image</h4>
                                        </div>
                                    </div>
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
    <!-- Edit Brand -->
@endforeach
