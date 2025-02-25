<?php $page = 'serviceChart-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Service Chart List" sub-title="Manage Service Chart" button="Add Service Chart" modal-id="add-serviceChart" />

           
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>

                    </div>
                    <!-- /Filter -->

                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table  datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">SL</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Created On</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @foreach ($serviceCharts as $serviceChart)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration + $serviceCharts->firstItem() - 1 }}
                                        </td>
                                        <td>{{ $serviceChart->name }}</td>
                                        <td>{{ number_format($serviceChart->price) }}</td>
                                        <td>{{ $serviceChart->code }}</td>
                                        <td>{{ $serviceChart->description }}</td>
                                        <td>{{ $serviceChart->created_at?->format('d M Y') }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-serviceChart-{{ $serviceChart->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.service-charts.destroy', $serviceChart->id) }}"
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

                                    <!-- Edit Brand -->
                                    <div class="modal fade" id="edit-serviceChart-{{ $serviceChart->id }}">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header">
                                                            <div class="page-title">
                                                                <h4>Edit Service Chart</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body new-employee-field">
                                                            <form class="editForm" data-id="{{ $serviceChart->id }}"
                                                                action="{{ route('admin.service-charts.update', $serviceChart->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Name*</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $serviceChart->name }}" name="name">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Price*</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $serviceChart->price }}" name="price">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Code*</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $serviceChart->code }}" name="code">
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="input-blocks summer-description-box transfer mb-3">
                                                                        <label>Description</label>
                                                                        <textarea class="form-control h-100" rows="5" name="description">{{ $serviceChart->description }}</textarea>
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

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

    <!-- Add Brand -->
    <div class="modal fade" id="add-serviceChart">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Create Service Chart</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.service-charts.store') }}" method="POST"
                                id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name*</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price*</label>
                                    <input type="text" name="price" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Code*</label>
                                    <input type="text" class="form-control" name="code">
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-blocks summer-description-box transfer mb-3">
                                        <label>Description</label>
                                        <textarea class="form-control h-100" rows="5" name="description"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Create Service Chart</button>
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
                        $('#add-serviceChart').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        SubmitBtn.prop('disabled', false);
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
                        $('.edit-serviceChart').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
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
