@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Supplier List" sub-title="Manage suppliers" button="Add Supplier" modal-id="add-supplier" />

            <!-- filter -->
            <div class="card table-list-card">
            <x-filter />
                    <!-- /Filter -->

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="no-sort">SL</th>
                                    <th>Name</th>
                                    <th>Zone</th>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @forelse($suppliers as $supplier)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration + $suppliers->firstItem() - 1 }}
                                        </td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->zone?->name }}</td>
                                        <td>{{ $supplier->phone ?? "N/A" }}</td>
                                        <td>{{ number_format((int)$supplier->balance, 0) }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-supplier-{{ $supplier->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
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

                                    <!-- Edit supplier -->
                                    <div class="modal fade" id="edit-supplier-{{ $supplier->id }}">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                            <div class="page-title">
                                                                <h4>Edit Supplier</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body new-employee-field">
                                                            <form class="editForm" data-id="{{ $supplier->id }}"
                                                                action="{{ route('admin.suppliers.update', $supplier->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Name*</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $supplier->name }}" name="name">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Zone*</label>
                                                                    <select class="select" name="zone_id">
                                                                        <option value="">Select Zone</option>
                                                                        @foreach ($zones as $zone)
                                                                            <option value="{{ $zone->id }}"
                                                                                {{ $supplier->zone_id == $zone->id ? 'selected' : '' }}>
                                                                                {{ $zone->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Phone*</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $supplier->phone }}" name="phone">
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
                                    <!-- Edit supplier -->
                                    @empty
                                   <tr class="text-center">
                                    <td colspan="7">No Supplier Found</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

    <!-- Add supplier -->
    <div class="modal fade" id="add-supplier">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Create Supplier</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#storeForm')[0].reset()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.suppliers.store') }}" method="POST" enctype="multipart/form-data"
                                id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name*</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Zone*</label>
                                    <select class="select" name="zone_id">
                                        <option value="">Select Zone</option>
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}">
                                                {{ $zone->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone*</label>
                                    <input type="text" class="form-control"
                                        value="{{ old('phone') }}" name="phone">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Balance</label>
                                    <input type="text" class="form-control"
                                        value="{{ old('balance') }}" name="balance">
                                </div>
                                

                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Create Supplier</button>
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
                        $('#add-supplier').modal('hide');
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
