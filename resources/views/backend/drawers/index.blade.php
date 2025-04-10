@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Drawer List" sub-title="Manage drawer" permission="drawer-create" button="Add drawer" modal-id="add-drawer" />

            <!-- filter -->
            <div class="card table-list-card">
                <x-filter>
                    <div class="col-lg-4 col-sm-3 col-12 ms-2" style="width: 200px;">
                        <div class="mb-3 add-product">
                            <div class="add-newplus">
                                <label class="form-label">Rack</label>
                            </div>
                            <select class="select filter-input" name="rack_id">
                                <option value="">Choose</option>
                                @foreach ($racks as $rack)
                                <option value="{{ $rack->id }}" @selected($rack->id == request()->rack_id)>{{ $rack->name }}</option>
                                @endforeach    
                            </select>
                        </div>
                    </div>
                </x-filter>
                    <!-- /Filter -->
                <div class="table-responsive" id="dataTable">
                    <x-drawers.table :drawers="$drawers" :racks="$racks" />
                </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

    <!-- Add Drawer -->
    <div class="modal fade" id="add-drawer">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Create Drawer</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                                onclick="$('#storeForm')[0].reset()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.drawers.store') }}" method="POST"
                                enctype="multipart/form-data" id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Rack*</label>
                                    <select class="select" name="rack_id">
                                        <option value="">Choose</option>
                                        @foreach ($racks as $rack)
                                            <option value="{{ $rack->id }}">{{ $rack->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Drawer Name*</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Save</button>
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
                        $('#add-brand').modal('hide');
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
                    if (response && response.message) {
                        toastr.error(response.message);
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
                    if (response && response.message) {
                        toastr.error(response.message);
                    }
                });
            });
        });
    </script>
@endpush
