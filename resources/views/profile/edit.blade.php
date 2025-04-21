<?php $page = 'profile'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Profile</h4>
                    <h6>User Profile</h6>
                </div>
            </div>
            <!-- /product list -->
            <div class="card">
                <div class="card-body">
                    <div class="profile-set">
                        <div class="profile-head">

                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="updateProfile">
                            @csrf
                            @method('PATCH')

                            <div class="profile-top">
                                <div class="profile-content">
                                    <div class="profile-contentimg">
                                        <img src="{{ $user->getFirstUrl('images') }}" alt="img"
                                            id="blah">
                                        <div class="profileupload">
                                            <input type="file" id="imgInp" name="image">
                                            <a href="javascript:void(0);"><img
                                                    src="{{ URL::asset('/build/img/icons/edit-set.svg') }}" alt="img"></a>
                                        </div>
                                    </div>
                                    <div class="profile-contentname">
                                        <h2>{{ $user->name }}</h2>
                                        <h4>Updates Your Photo and Personal Details.</h4>
                                    </div>
                                </div>
                                <!-- <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-submit me-2" data-bs-target="" type="submit">Save</a>
                                    <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                                </div> -->
                            </div>
                    
                            <div class="row mt-4">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="input-blocks">
                                        <label class="form-label">User Name</label>
                                        <input type="text" class="form-control" value="{{ $user->name }}" name="name">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="input-blocks">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}" name="email" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="input-blocks">
                                        <label class="form-label">Phone</label>
                                        <input type="text" value="{{ $user->phone }}" name="phone" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-submit me-2" id="profile_update" type="submit">Submit</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-cancel">Cancel</a>
                            </div>
                        </form>
                        <div class="profile-bottom mt-4">
                            <form action="{{ route('password.update') }}" method="post" id="changePassword">
                                @csrf
                                @method('PUT')
                                <div class="accordion-item mb-3 rounded-3 overflow-hidden border border-1">
                                    <h2 class="accordion-header" id="basicInfoHeading">
                                        <button class="accordion-button fw-bold p-3" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#basicInfo" aria-expanded="true" aria-controls="basicInfo">
                                            Change Password
                                        </button>
                                    </h2>
                                    <div id="basicInfo" class="accordion-collapse collapse show" aria-labelledby="basicInfoHeading"
                                        data-bs-parent="#changePassword">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-lg-6 col-sm-12 mt-4">
                                                    <div class="input-blocks">
                                                        <label class="form-label">Old Password</label>
                                                        <div class="pass-group">
                                                            <input type="password" class="pass-input" name="current_password">
                                                            <span class="fas toggle-password fa-eye-slash"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-12">
                                                    <div class="input-blocks">
                                                        <label class="form-label">New Password</label>
                                                        <div class="pass-group">
                                                            <input type="password" class="pass-input" name="password" id="new_password">
                                                            <span class="fas toggle-password fa-eye-slash"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-12">
                                                    <div class="input-blocks">
                                                        <label class="form-label">Confirm Password</label>
                                                        <div class="pass-group">
                                                            <input type="password" class="pass-input" name="password_confirmation">
                                                            <span class="fas toggle-password fa-eye-slash"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn btn-submit me-2" id="password_update" type="submit">Update Password</button>
                                                    <a href="{{ route('dashboard') }}" class="btn btn-cancel">Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#updateProfile').submit(function(e) {
                e.preventDefault();
                let submitBtn = $('#profile_update');
                submitBtn.prop('disabled', true);
                let formData = new FormData(this);
                $.ajax({
                    type: $(this).attr('method'),
                    url:$(this).attr('action'),
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function(response) {
                    if (response.type == 'success') {
                        toastr.success('Profile updated successfully');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        submitBtn.prop('disabled', false);
                        toastr.error('Profile not updated');
                    }
                }).fail(function(error) {
                    submitBtn.prop('disabled', false);
                    let response = error.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                });
            });
            // Change Password
            $('#changePassword').submit(function(e) {
                e.preventDefault();
                let submitBtnPassword = $('#password_update');
                submitBtnPassword.prop('disabled', true);
                let data = new FormData(this);
                data.append('_token', '{{ csrf_token() }}');
                data.append('_method', 'PUT');
                $.ajax({
                    type: $(this).attr('method'),
                    url:$(this).attr('action'),
                    data: data,
                    processData: false,
                    contentType: false,
                }).done(function(response) {
                    if (response.type == 'success') {
                        toastr.success('Password updated successfully');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        submitBtnPassword.prop('disabled', false);
                        toastr.error('Password not updated');
                    }
                }).fail(function(error) {
                    submitBtnPassword.prop('disabled', false);
                    let response = error.responseJSON;
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
