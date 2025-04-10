<?php $page = 'account-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Account List" sub-title="Manage Account" permission="account-create" button="Add Account" modal-id="add-account" />

                    <!-- filter -->
           
                    <div class="card table-list-card">
                    <x-filter>
                        <div class="col-lg-4 col-sm-3 col-12 ms-2" style="width: 200px;">
                            <div class="mb-3 add-product">
                                <div class="add-newplus">
                                    <label class="form-label">Account Type</label>
                                </div>
                                <select class="select filter-input" name="account_Type">
                                    <option value="">Choose</option>
                                    <option value="cash" @selected(request()->account_Type == 'cash')>Cash</option>
                                    <option value="bank" @selected(request()->account_Type == 'bank')>Bank</option>
                                </select>
                            </div>
                        </div>
                    </x-filter>

                    <!-- /Filter -->
                    <div class="table-responsive" id="dataTable">
                        <x-accounts.table :accounts="$accounts"/>                   
                    </div>
                </div>
            <!-- /Account list -->
        </div>
    </div>

    <!-- Add Account -->
    <div class="modal fade" id="add-account">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Create Account</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#storeForm')[0].reset()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.accounts.store') }}" method="POST"
                                id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name*</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type*</label>
                                    <select class="select" name="type">
                                        <option value="">Choose</option>
                                        <option value="1">Cash</option>
                                        <option value="2">Bank</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Balance*</label>
                                    <input type="text" class="form-control" name="balance">
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
            $('#storeForm').submit(async function(e) {
            e.preventDefault();
               $confirm = await Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to update the balance manually later!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel'
                });

                if(! $confirm.isConfirmed){
                    return false;
                }

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
                        $('#add-account').modal('hide');
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

            $(document).on('submit', '.editForm', function(e) {
                e.preventDefault();
                let form = $(this);
                let formData = new FormData(this);

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(response) {
                    if (response.type === 'success') {
                        form.closest('.modal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                }).fail(function(xhr) {
                    let response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error("Something went wrong.");
                    }
                });
            });
        });
    </script>
@endpush
