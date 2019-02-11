<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        $role = Role::findById(2);
//        $permission = Permission::findById(2);
//        print_r($permission);
//        $permission->removeRole('admin');
//        $role->givePermissionTo($permission->name);
//        $role->revokePermissionTo($permission->name);
//        auth()->user()->assignRole('admin');
        return view('home');
    }
}
