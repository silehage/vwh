<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PermissionController extends Controller
{
   public function index()
    {
        $roles = Role::with('permissions')->where('id', '<>', 1)->get()->map(function ($el) {
            return [
               'id' => $el->id,
               'name' => $el->name,
               'sort' => $el->sort,
               'permissions' => $el->permissions->pluck('id')
            ];
         });
        $modules = Permission::MODULES;
        $permissions = Permission::all();

        return Inertia::render('User/PermissionIndex', compact('roles', 'modules', 'permissions'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
            'permission_id' => 'required',
        ]);

        $role = Role::findOrFail($request->role_id);

        $role->permissions()->toggle($request->permission_id);
        Cache::flush();
        return back();
        return to_route('users.index');
    }
}
