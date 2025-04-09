<?php

namespace App\Modules\RolePermission\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-create')->only('create', 'store');
        $this->middleware('permission:user-list')->only('index');
        $this->middleware('permission:user-update')->only('edit', 'update');
        $this->middleware('permission:user-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        $users = User::query()
            ->with('roles', 'media', 'zone')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $roles = Role::select('id', 'name')->get();

        if (request()->ajax()) {
            return view('components.users.table', ['users' => $users])->render();
        }

        return view('backend.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::get();
        $zones = Zone::select('id', 'name')->get();
        $groupedPermissions = Permission::select('group_name', 'id', 'name')
            ->orderBy('group_name')->get()->groupBy('group_name');

        return view('backend.users.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:8',
            'role_id' => auth()->user()->role == User::$SUPER_ADMIN ? 'nullable' : 'required|exists:roles,id',
            'zone_id' => auth()->user()->role == User::$SUPER_ADMIN ? 'required|exists:zones,id' : 'nullable',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => auth()->user()->role == User::$SUPER_ADMIN ? User::$IN_CHARGE : User::$FOREMAN,
                'zone_id' => auth()->user()->role == User::$SUPER_ADMIN ? $request->zone_id : auth()->user()->zone_id,
            ]);

            if (auth()->user()->role != User::$SUPER_ADMIN) {
                $role = Role::find($request->role_id);

                if ($user) {
                    $user->assignRoleToUser($role);
                }
                if (isset($request->permissions)) {
                    $user->syncPermissions($request->permissions);
                }
            }

            return $user->save();
        });

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $roles = Role::get();
        $user = User::findOrFail($id);
        $zones = Zone::select('id', 'name')->get();

        $groupedPermissions = Permission::select('group_name', 'id', 'name')
            ->orderBy('group_name')->get()->groupBy('group_name');

        return view('backend.users.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email,'.$id,
            'phone' => 'required|unique:users,phone,'.$id,
            'role_id' => auth()->user()->role == User::$SUPER_ADMIN ? 'nullable' : 'required|exists:roles,id',
            'zone_id' => auth()->user()->role == User::$SUPER_ADMIN ? 'required|exists:zones,id' : 'nullable',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'zone_id' => auth()->user()->role == User::$SUPER_ADMIN ? $request->zone_id : auth()->user()->zone_id,
        ]);
        
        $role = Role::find($request->role_id);
        if ($user && $role) {
            $user->syncRoles($role);
        }

        if (isset($request->permissions)) {
            $user->permissions()->detach();
            $user->assignPermissionToUser($request->permissions);
        } else {
            $user->permissions()->detach();
        }
        Cache::forget('user_permissions'.$user->id);

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
