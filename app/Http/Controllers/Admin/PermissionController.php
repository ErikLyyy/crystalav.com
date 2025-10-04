<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role_permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'role']);
            return $next($request);
        });
    }
    function show()
    {
        // if (!Gate::allows('permissions.add')) {
        //     abort(403);
        // }
        $permissions = Permission::all()
            ->groupBy(function ($permission) {
                return explode('.', $permission->slug)[0];
            });
        return view('admin.permission.show', compact('permissions'));
    }
    function store(Request $request)
    {
        // if (!Gate::allows('permissions.add')) {
        //     abort(403);
        // }
        $request->validate(
            [
                'name' => ['required'],
                'slug' => ['required']
            ]
        );
        Permission::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description
        ]);
        return redirect('admin/permission');
    }
    function delete($id)
    {
        // if (!Gate::allows('permissions.delete')) {
        //     abort(403);
        // }
        $role_permission = Role_permission::all()->where('permission_id', $id);
        foreach ($role_permission as $item) {
            $item->delete();
        }
        Permission::find($id)->delete();
        return redirect('admin/permission');
    }
    function edit($id)
    {
        // if (!Gate::allows('permissions.edit')) {
        //     abort(403);
        // }
        $permissions = Permission::all()
            ->groupBy(function ($permission) {
                return explode('.', $permission->slug)[0];
            });
        $permission = Permission::find($id);
        return view('admin.permission.edit', compact('permissions', 'permission'));
    }
    function update(Request $request, $id)
    {
        // if (!Gate::allows('permissions.edit')) {
        //     abort(403);
        // }
        $request->validate(
            [
                'name' => ['required'],
                'slug' => ['required']
            ],
            [
                'required' => "Không được bỏ trống :attribute"
            ],
            [
                'name' => "Tên quyền",
                'slug' => 'Slug'
            ]
        );
        Permission::find($id)->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description
        ]);
        return redirect('admin/permission');
    }
}
