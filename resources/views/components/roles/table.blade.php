<table class="table" id="dTable">
    <thead>
        <tr>
            <th>SL No</th>
            <th>Role Name</th>
            <th>Guard Name</th>
            <th>Permissions</th>
            @permission(['role-update', 'role-delete'])
            <th class="text-end">Action</th>
            @endpermission
        </tr>
    </thead>
    <tbody>
        @forelse ($roles as $role)
            <tr>
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
                @permission(['role-update', 'role-delete'])
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        @permission('role-update')
                        <a href="{{ route('roles.edit', $role->id) }}" class="me-2 p-2">
                            <i data-feather="edit" class="feather-edit"></i>
                        </a>
                        @endpermission
                        @permission('role-delete')
                        <a class="me-2 p-2 confirm-text2" href="javascript:void(0);">
                            <i data-feather="trash-2" class="feather-trash-2"></i>
                        </a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="delete-form d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endpermission
                    </div>
                </td>
                @endpermission
            </tr>
            @empty
            <tr class="text-center">
            <td colspan="7">No Role Found</td>
        </tr>
        @endforelse
    </tbody>
</table>
<x-pagination :paginator="$roles" />