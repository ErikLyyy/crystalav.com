<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Role_Permission;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    function show()
    {
        if (!Gate::allows('role.show')) {
            abort(403);
        }
        $roles = Role::all();
        return view('admin.role.show', compact('roles'));
    }
    function add()
    {
        if (!Gate::allows('role.add')) {
            abort(403);
        }
        $permissions = Permission::all()
            ->groupBy(function ($permission) {
                return explode('.', $permission->slug)[0];
            });
        return view('admin/role/add', compact('permissions'));
    }
    function store(Request $request)
    {
        if (!Gate::allows('role.add')) {
            abort(403);
        }
        $request->validate(
            [
                'name' => ['required'],
            ]
        );
        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);
        $role->permissions()->attach($request->input('permission_id'));
        return redirect('admin/role');
    }
    function delete($id)
    {
        if (!Gate::allows('role.delete')) {
            abort(403);
        }
        $role_permission = Role_Permission::all()->where('role_id', $id);
        foreach ($role_permission as $item) {
            $item->delete();
        }
        $user_role = User_role::where('role_id', $id)->get();
        foreach ($user_role as $v) {
            $v->delete();
        }
        Role::find($id)->delete();
        return redirect('admin/role');
    }
    function handleAction(Request $request)
    {
        if (!Gate::allows('role.delete')) {
            abort(403);
        }
        if (!isset($request->checkItem)) {
            return redirect('admin/role')->with('danger', 'You have not selected anything yet');
        }
        if ($request->actions == "delete") {
            foreach ($request->checkItem as $item) {
                $role_permission = Role_Permission::where('role_id', $item)->get();
                foreach ($role_permission as $v) {
                    $v->delete();
                }
                $user_role = User_role::where('role_id', $item)->get();
                foreach ($user_role as $v) {
                    $v->delete();
                }
                $role = Role::find($item);
                $role->delete();
            }
            return redirect('admin/role')->with('status', 'Deleted successfully!');
        } else {
            return redirect('admin/role')->with('danger', 'You have not chosen action yet');
        }
    }
    function edit($id)
    {
        if (!Gate::allows('role.edit')) {
            abort(403);
        }
        $permissions = Permission::all()
            ->groupBy(function ($permission) {
                return explode('.', $permission->slug)[0];
            });
        $role = Role::find($id);
        return view('admin.role.edit', compact('role', 'permissions'));
    }
    function update(Request $request, $id)
    {
        if (!Gate::allows('role.edit')) {
            abort(403);
        }
        $request->validate(
            [
                'name' => ['required'],
            ]
        );
        Role::find($id)->update([
            'name' => $request->name,
            'description' => $request->description
        ]);
        $role = Role::find($id);
        $role->permissions()->sync($request->input('permission_id', []));
        return redirect('admin/role');
    }
}
