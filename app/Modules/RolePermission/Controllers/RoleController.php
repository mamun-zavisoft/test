<?php

namespace App\Modules\RolePermission\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-create')->only('create', 'store');
        $this->middleware('permission:role-list')->only('index');
        $this->middleware('permission:role-update')->only('edit', 'update');
        $this->middleware('permission:role-delete')->only('destroy');
    }

    public function index()
    {
        $search = request()->input('search', '');
        $perPage = request('per_page', 10);
        $roles = Role::query()
            ->with('permissions')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('guard_name', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        if (request()->ajax()) {
            return view('components.roles.table', ['roles' => $roles])->render();
        }

        return view('backend.roles.index', compact('roles'));
    }

    public function create()
    {
        $groupedPermissions = Permission::select('group_name', 'id', 'name')
            ->orderBy('group_name')->get()->groupBy('group_name');

        return view('backend.roles.create', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name',
            'guard_name' => 'required|in:web,admin',
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,name',
        ]);
        $role = Role::create($data);
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $groupedPermissions = Permission::select('group_name', 'id', 'name')
            ->orderBy('group_name')->get()->groupBy('group_name');

        return view('backend.roles.edit', compact('role', 'groupedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role = Role::with('users')->where('id', $role->id)->first();
        $data = $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'guard_name' => 'required|in:web,admin',
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,name',
        ]);

        $role->update($data);
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        // Reset permissions for all users with this role
        $users = $role->users;

        foreach ($users as $user) {
            Cache::forget('user_permissions'.$user->id);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->load('users');
        if ($role->users->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete this role because users are assigned to it.');
        }

        DB::table('roles')->where('id', $id)->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
