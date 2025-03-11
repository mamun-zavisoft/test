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

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
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
                    </div>
                        <div class="row">
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
                            <div>
                                <h4>Change Password</h4>

                                <div class="col-lg-6 col-sm-12 mt-4">
                                    <div class="input-blocks">
                                        <label class="form-label">Old Password</label>
                                        <div class="pass-group">
                                            <input type="password" name="old_password">
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="input-blocks">
                                        <label class="form-label">New Password</label>
                                        <div class="pass-group">
                                            <input type="password" name="new_password">
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="input-blocks">
                                        <label class="form-label">Confirm Password</label>
                                        <div class="pass-group">
                                            <input type="password" name="new_password_confirmation">
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-submit me-2" type="submit">Submit</button>
                                <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection
