<?php $page = 'register-3'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="account-content">
        <div class="login-wrapper login-new">
            <div class="login-content user-login">
                <div class="login-logo">
                    <img src="{{ URL::asset('/build/img/logo.png') }}" alt="img">
                    <a href="{{ url('index') }}" class="login-logo logo-white">
                        <img src="{{ URL::asset('/build/img/logo-white.png') }}" alt="">
                    </a>
                </div>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="login-userset">
                        <div class="login-userheading">
                            <h3>Register</h3>
                            <h4>Create New Account</h4>
                        </div>
                        <div class="form-login">
                            <label>Name*</label>
                            <div class="form-addons">
                                <input type="text" class="form-control" name="name">
                                <img src="{{ URL::asset('/build/img/icons/user-icon.svg') }}" alt="img">
                            </div>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-login">
                            <label>Email Address</label>
                            <div class="form-addons">
                                <input type="text" class="form-control" name="email">
                                <img src="{{ URL::asset('/build/img/icons/mail.svg') }}" alt="img">
                            </div>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-login">
                            <label>Phone*</label>
                            <div class="form-addons">
                                <input type="text" class="form-control" name="phone">
                                <span class="fas toggle-password fa-phone"></span>
                            </div>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-login">
                            <label>Password*</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input" name="password">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-login">
                            <label>Confirm Password*</label>
                            <div class="pass-group">
                                <input type="password" class="pass-inputs" name="password_confirmation">
                                <span class="fas toggle-passwords fa-eye-slash"></span>
                            </div>
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-login authentication-check">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="custom-control custom-checkbox justify-content-start">
                                        <div class="custom-control custom-checkbox">
                                            {{-- <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                                                <input type="checkbox" name="terms">
                                                <span class="checkmarks"></span>I agree to the <a href="#"
                                                    class="hover-a">Terms & Privacy</a>
                                            </label> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">Sign Up</button>
                        </div>
                        <div class="signinform">
                            <h4>Already have an account ? <a href="{{ route('login') }}" class="hover-a">Sign In
                                    Instead</a></h4>
                        </div>
                        {{-- <div class="form-setlogin or-text">
                            <h4>OR</h4>
                        </div> --}}
                        <div class="form-sociallink">
                            <ul class="d-flex">
                                <li>
                                    <a href="javascript:void(0);" class="facebook-logo">
                                        <img src="{{ URL::asset('/build/img/icons/facebook-logo.svg') }}" alt="Facebook">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="{{ URL::asset('/build/img/icons/google.png') }}" alt="Google">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="apple-logo">
                                        <img src="{{ URL::asset('/build/img/icons/apple-logo.svg') }}" alt="Apple">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                <p>Copyright &copy; 2023 DreamsPOS. All rights reserved.</p>
            </div>
        </div>
    </div>
@endsection
