@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="New User" sub-title="Add New User" button="Go Back" back-button-route="users.index" />


            <div class="card">
                <div class="card-body">
                    <form id="saveButton" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST" 
                        class="needs-validation" novalidate="">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="name">Name<span class="manitory">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" placeholder="Enter name">
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Phone<span class="manitory">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" id="phoneNumber" placeholder="Enter phone">
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" id="email" placeholder="Enter email">
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="password">Password<span class="manitory">*</span></label>
                                    <div class="pass-group">
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
                                        <span class="fas fa-eye toggle-password"></span>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @if (Auth::user()->role != 1)
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="role">Role<span class="manitory">*</span></label>
                                        <select id="role" name="role_id" class="select">
                                            <option value="">Select Role</option>
                                            @foreach ($roles as $item)
                                                <option value="{{ $item->id }}" {{ old('role_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->role == 1)
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="zone">Zone<span class="manitory">*</span></label>
                                        <select id="zone" name="zone_id" class="select">
                                            @foreach ($zones as $zone)
                                                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('zone_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Permissions</h5>
                                <div class="form-check float-start mt-1">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultAll">
                                    <label class="form-check-label" for="flexCheckDefaultAll">Select All</label>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div class="row">
                                    @foreach ($groupedPermissions as $groupName => $permissions)
                                        @php
                                            $groupLoopId = $loop->iteration;
                                        @endphp
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="card permission-card mb-4">
                                                <div class="card-header permission-header">
                                                    <div class="form-check">
                                                        <input class="form-check-input group-checkbox" type="checkbox"
                                                            value="{{ $groupLoopId }}" id="groupID{{ $groupLoopId }}">
                                                        <label class="form-check-label fw-bold" for="groupID{{ $groupLoopId }}">
                                                            {{ $groupName }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($permissions as $permission)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input substituted group_id{{ $groupLoopId }}"
                                                                name="permissions[]" type="checkbox"
                                                                value="{{ $permission->name }}" 
                                                                id="flexCheckDefault{{ $permission->id }}">
                                                            <label class="form-check-label" for="flexCheckDefault{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 sticky-footer">
                                <button type="submit" class="btn btn-submit me-2">Save</button>
                                <a href="{{ route('users.index') }}" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .permission-card {
        border: 1px solid #e9ecef;
        border-radius: 5px;
    }
    .permission-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    .manitory {
        color: red;
        margin-left: 2px;
    }
    .pass-group {
        position: relative;
    }
    .pass-group .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize select2
        $('.select').select2({
            width: '100%'
        });

        // Group checkbox handler
        $('.group-checkbox').change(function() {
            var groupName = $(this).val();
            var isChecked = $(this).prop('checked');
            $('.group_id' + groupName).prop('checked', isChecked);
        });

        // Select all checkbox handler
        $('#flexCheckDefaultAll').click(function() {
            $('input[type = checkbox]').prop('checked', $(this).is(':checked'));
        });

        // Password toggle
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $(this).parent().find("input");
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    });
</script>
@endpush