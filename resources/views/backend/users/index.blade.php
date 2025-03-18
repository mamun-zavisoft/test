<?php $page = 'users'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            
            <x-breadcrumb title="User List" sub-title="Manage Your Users" button="Add New User" button-route="users.create" />


            <!-- filter -->
            <div class="card table-list-card">
                    <x-filter />
                    <!-- /Filter -->

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    {{-- <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th> --}}
                                    <th>SL</th>
                                    <th>User Name</th>
                                    <th>Phone</th>
                                    <th>email</th>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    {{-- <th>Status</th> --}}
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        {{-- <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td> --}}
                                        <td>{{  $loop->iteration + $users->firstItem() - 1 }}</td>
                                        <td>
                                            <div class="userimgname">
                                                <a href="javascript:void(0);" class="userslist-img bg-img">
                                                    <img src="{{ $user->image ?: asset('build/img/no-image.svg') }}"
                                                        alt="product">
                                                </a>
                                                <div>
                                                    <a href="javascript:void(0);">{{ $user->name }}</a>
                                                </div>

                                            </div>
                                        </td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @php
                                                if($user->role == 1){
                                                    $role_name = "Super Admin";
                                                } elseif ($user->role == 2) {
                                                    $role_name = "In Charge";
                                                } else {
                                                    $role_name = $user->roles?->pluck('name')?->implode(', ');
                                                }
                                            @endphp
                                            {{ $role_name ?? "N/A" }}
                                        </td>
                                        <td>
                                            @php
                                                $permissions = $user->permissions
                                                    ->merge($user->roles->flatMap->permissions)
                                                    ->unique('id');
                                            @endphp
                                            <div class="d-flex flex-wrap gap-2" style="max-height: 50px; overflow-y: auto;">
                                                @foreach ($permissions as $permission)
                                                    <span class="badge bg-light text-dark d-flex align-items-center">
                                                        <span class="me-2"
                                                            style="width: 8px; height: 8px; background: {{ $user->permissions->contains('id', $permission->id) ? 'red' : 'blue' }}; border-radius: 50%;"></span>
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        {{-- <td><span class="badge badge-linedanger">Inactive</span></td> --}}
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                {{-- <a class="me-2 p-2 mb-0" href="javascript:void(0);">
                                                    <i data-feather="eye" class="action-eye"></i>
                                                </a> --}}
                                                <a href="{{ route('users.edit', $user->id) }}" class="me-2 p-2 mb-0">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                
                                                @if ($user->role != 1 || auth()->user()->id != $user->id)    
                                                    <a class="me-2 confirm-text p-2 mb-0" href="javascript:void(0);">
                                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                                    </a>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="text-center mt-4">No Data Found</div>
                                @endforelse

                            </tbody>
                        </table>
                        <x-pagination :paginator="$users" />
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
            if (!$.fn.DataTable.isDataTable('#usersList')) {
                $('#usersList').DataTable({
                    lengthChange: false,
                    pageLength: 10,
                    searching: true,
                    ordering: true,
                    info: true,
                    language: {
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        search: "",
                        searchPlaceholder: "Search...",
                        zeroRecords: "No matching records found"
                    },
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false
                    }],
                    drawCallback: function() {
                        // Reinitialize Feather icons after DataTable draw
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    }
                });
            }

            $('.confirm-text').on('click', function(e) {
                e.preventDefault();

                // Get the associated delete form
                var deleteForm = $(this).closest('tr').find('.delete-form');

                // Show the SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, submit the form
                        deleteForm.submit();
                    }
                });
            });

        });
    </script>
@endpush
