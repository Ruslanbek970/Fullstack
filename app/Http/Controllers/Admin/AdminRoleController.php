<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()->with(['permissions', 'users'])->orderBy('name')->get();
        $permissions = Permission::query()->orderBy('name')->get();

        return view('pages.admin.roles', compact('roles', 'permissions'));
    }
}
