@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    User Update
                @endslot
                @slot('li_1')
                    Users
                @endslot
                @slot('li_2')
                    Edit User
                @endslot
            @endcomponent

            <div class="card">
                <div class="card-body">
                    <form id="saveButton" action="{{ route('users.update',$user->id) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate="">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="name">Name<span class="manitory">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name ?? '' }}" id="name" placeholder="Enter name">
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Phone<span class="manitory">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}" id="phoneNumber" placeholder="Enter phone">
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="email">Email<span class="manitory">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}" id="email" placeholder="Enter email">
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="role">Role<span class="manitory">*</span></label>
                                    <select id="role" name="role_id" class="select">
                                        @foreach ($roles as $item)
                                            <option {{ $user->roles->contains('id', $item->id) ? 'selected' : '' }} value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                                        <div class="col-lg-6">
                                            <div class="card permission-card mb-4">
                                                <div class="card-header permission-header">
                                                    <div class="form-check">
                                                        <input class="form-check-input group-checkbox" type="checkbox"
                                                            value="{{ $groupLoopId }}"
                                                            {{ $permissions->every(fn($permission) => $user->permissions->contains($permission->id)) ? 'checked' : '' }}
                                                            id="groupID{{ $groupLoopId }}">
                                                        <label class="form-check-label fw-bold" for="groupID{{ $groupLoopId }}">
                                                            {{ $groupName }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($permissions as $permission)
                                                        <div class="col-lg-6">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input substituted group_id{{ $groupLoopId }}"
                                                                    {{ $user->permissions->contains($permission->id) ? 'checked' : '' }}
                                                                    name="permissions[]" type="checkbox"
                                                                    value="{{ $permission->name }}" 
                                                                    id="flexCheckDefault{{ $permission->id }}">
                                                                <label class="form-check-label" for="flexCheckDefault{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
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
                                <button type="submit" class="btn btn-submit me-2">Update</button>
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
    });
</script>
@endpush