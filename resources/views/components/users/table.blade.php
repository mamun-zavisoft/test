@php
    $authUser = auth()->user();
@endphp
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
            <th>zone</th>
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
                            <span class="{{ $authUser->id == $user->id ? 'badge bg-primary text-white': '' }}">{{ $user->name }}</span>
                        </div>

                    </div>
                </td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->email ?? '-' }}</td>
                <td>{{ $user->zone?->name ?? "-" }}</td>
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
                        @forelse ($permissions as $permission)
                            <span class="badge bg-light text-dark d-flex align-items-center">
                                <span class="me-2"
                                    style="width: 8px; height: 8px; background: {{ $user->permissions->contains('id', $permission->id) ? 'red' : 'blue' }}; border-radius: 50%;"></span>
                                {{ $permission->name }}
                            </span>
                        @empty
                            {{-- <span class="badge bg-light text-dark">  </span> --}}
                        @endforelse
                    </div>
                </td>
                {{-- <td><span class="badge badge-linedanger">Inactive</span></td> --}}
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        @permission('user-update')
                        @if ($authUser->id != $user->id && $user->role != 1 || $authUser->role == 1)
                            <a href="{{ route('users.edit', $user->id) }}" class="me-2 p-2 mb-0">
                                <i data-feather="edit" class="feather-edit"></i>
                            </a>
                        @endif
                        @endpermission
                        
                        @permission('user-delete')
                        @if ($user->role != 1 && $authUser->id != $user->id)    
                            <a class="me-2 confirm-text p-2 mb-0" href="javascript:void(0);">
                                <i data-feather="trash-2" class="feather-trash-2"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                        @endpermission
                    </div>
                </td>
            </tr>
        @empty

            <tr class="text-center">
                <td colspan="8">No data found</td>
            </tr>
        @endforelse

    </tbody>
</table>
<x-pagination :paginator="$users" />