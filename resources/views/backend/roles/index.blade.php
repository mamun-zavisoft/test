<?php $page = 'roles'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Role List" sub-title="Manage Your Roles" button="Add New Role" button-route="roles.create" />

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

           
            <div class="card table-list-card">
                    <x-filter />

                    <div class="table-responsive">
                        <table class="table" id="dTable">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>SL No</th>
                                    <th>Role Name</th>
                                    <th>Guard Name</th>
                                    <th>Permissions</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->guard_name }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2" style="max-height: 50px; overflow-y: auto;">
                                                @foreach ($role->permissions as $permission)
                                                    <span class="badge bg-light text-dark d-flex align-items-center">
                                                        <span class="me-2" style="width: 8px; height: 8px; background: red; border-radius: 50%;"></span>
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a href="{{ route('roles.edit', $role->id) }}" class="me-2 p-2">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a class="me-2 p-2 confirm-text" href="javascript:void(0);">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="delete-form d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="text-center">
                                    <td colspan="7">No Role Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :paginator="$roles" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {


    // Delete confirmation
    $('.confirm-text').on('click', function() {
        if (confirm('Are you sure you want to delete this role?')) {
            $(this).closest('tr').find('.delete-form').submit();
        }
    });

    // Select all checkbox
    $('#select-all').on('change', function() {
        $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    });
});
</script>
@endpush