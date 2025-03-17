@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Category List" sub-title="Manage Your Categories" button="Add Category" modal-id="add-category" />

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <x-filter />

                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="no-sort">SL</th>
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>Created On</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @forelse ($categories as $category)
                                @php
                                    $image = $category->image;
                                @endphp
                                    <tr>
                                        <td>
                                            {{ $loop->iteration + $categories->firstItem() - 1 }}
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td><span class="d-flex"><img
                                                    src="{{ $image ?: asset('build/img/no-image.svg') }}"
                                                    style="width: 50px; height: 50px;" 
                                                    alt=""></span></td>
                                        <td>{{ $category->created_at->format('d M Y') }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-category-{{ $category->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                    method="post" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="confirm-text2 p-2" href="javascript:void(0);">
                                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                                    </a>
                                                </form>
                                            </div>

                                        </td>
                                    </tr>

                                    <!-- Edit category -->
                                    <div class="modal fade" id="edit-category-{{ $category->id }}">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                            <div class="page-title">
                                                                <h4>Edit Category</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body new-employee-field">
                                                            <form class="editForm" data-id="{{ $category->id }}"
                                                                action="{{ route('admin.categories.update', $category->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Category*</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $category->name }}" name="name">
                                                                </div>
                                                                <label class="form-label">Logo</label>
                                                                <div class="profile-pic-upload mb-3 image-container">
                                                                    <div class="profile-pic brand-pic">
                                                                        <span>
                                                                            <img src="{{ $image ?: asset('build/img/icons/upload.svg') }}"
                                                                                class="image-preview" alt="">
                                                                        </span>
                                                                        <a href="javascript:void(0);"
                                                                            class="remove-photo d-none">
                                                                            <i data-feather="x" class="x-square-add"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="image-upload mb-0">
                                                                        <input class="image-input" type="file"
                                                                            name="image">
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
                                    <!-- Edit category -->
                                @empty
                                    <tr class="text-center">
                                        <td colspan="7">No Category Found</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <x-pagination :paginator="$categories" />
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

    <!-- Add category -->
    <div class="modal fade" id="add-category">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Create Category</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#storeForm')[0].reset()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                                id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <label class="form-label">Logo</label>
                                <div class="profile-pic-upload mb-3 image-container">
                                    <div class="profile-pic brand-pic">
                                        <span>
                                            <img src="{{ asset('build/img/icons/upload.svg') }}"
                                                class="image-preview" alt="">
                                        </span>
                                        <a href="javascript:void(0);" class="remove-photo d-none">
                                            <i data-feather="x" class="x-square-add"></i>
                                        </a>
                                    </div>
                                    <div class="image-upload mb-0">
                                        <input class="image-input" type="file" name="image">
                                        <div class="image-uploads">
                                            <h4>Change Image</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Create Category</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ajax call for store
        $(document).ready(function() {
            $('#storeForm').submit(function(e) {
                e.preventDefault();
                let SubmitBtn = $('#submit_btn');
                SubmitBtn.prop('disabled', true);
                let formData = new FormData(this);
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function(response) {
                    if (response.type == 'success') {
                        $('#add-category').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                }).fail(function(xhr) {
                    SubmitBtn.prop('disabled', false);
                    $('#submit_btn').attr('disabled', false);
                    let response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                });
            });

            $('.editForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function(response) {
                    if (response.type == 'success') {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        SubmitBtn.prop('disabled', false);
                        toastr.error(response.message);
                    }
                }).fail(function(xhr) {
                    $('#submit_btn').attr('disabled', false);
                    let response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                });
            });
        });
    </script>
@endpush
