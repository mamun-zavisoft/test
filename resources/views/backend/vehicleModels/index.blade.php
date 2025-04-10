@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Vehicle Model List" sub-title="Manage vehicle model" permission="vehicle-model-create" button="Add vehicle model" modal-id="add-vehicleModel" />

            <!-- filter -->
            <div class="card table-list-card">
                <x-filter />

                    <!-- /Filter -->
                <div class="table-responsive" id="dataTable">
                    <x-vehicleModels.table :vehicleModels="$vehicleModels" />
                </div>
                </div>
            </div>
            <!-- /vehicle models list -->
        </div>
    </div>

    <!-- Add Vehicle Model -->
    <div class="modal fade" id="add-vehicleModel">
        <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 50%;">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Create Vehicle Models</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                                onclick="$('#storeForm')[0].reset()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.vehicle-models.store') }}" method="POST"
                                id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name*</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Manufacturer*(Brand)</label>
                                    <input type="text" name="manufacturer" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Engine CC*</label>
                                    <input type="number" name="engine_cc" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fuel Capacity*(Liter)</label>
                                    <input type="number" name="fuel_capacity" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payload Capacity*(KG)</label>
                                    <input type="number" name="payload_capacity" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Body Length*(Feet)</label>
                                    <input type="number" name="body_length" class="form-control">
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
                        $('#add-vehicleModel').modal('hide');
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

