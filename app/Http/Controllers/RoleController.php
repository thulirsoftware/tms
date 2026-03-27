<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Role;
use App\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        // ✅ Using Validator instead of $request->validate()
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator) // send errors to view
                ->withInput(); // keep old input
        }

        $role = Role::create(['name' => $request->name]);
        $role->permissions()->sync($request->permissions);

        return redirect('/Admin/roles')->with('success', 'Role created successfully!');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // ✅ Validation with ignore current role id
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role->update(['name' => $request->name]);
        $role->permissions()->sync($request->permissions);

        return redirect('/Admin/roles')->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect('/Admin/roles')->with('success', 'Role deleted successfully!');
    }
}
