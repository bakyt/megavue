<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RoleResource;
use App\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $method = explode('.', $route = \request()->route()->action["as"]);
        if($method[1] == 'show') $route = $method[0].'.index';
        elseif($method[1] == 'update') $route = $method[0].'.edit';
        elseif($method[1] == 'store') $route = $method[0].'.create';
        $this->middleware('permission:'.$route)->only($method[1]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return RoleResource::collection(Role::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role();
        return response()->json(Section::getByUserPermissions($role->permissions()->getResults(), 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RoleResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>['required', 'string', 'max:50', 'unique:roles'],
        ]);
        $role = new Role();
        $role->name = $request->name;
        if($request->get('guard_name')) $role->guard_name=$request->guard_name;
        $role->save();
        if($give_permissions = $request->get('give_permissions')){
            if (is_array($give_permissions)) foreach ($give_permissions as $permission){
                if(!Permission::where('name', '=', $permission)->first())
                    $permission = Permission::create(['name'=>$permission, 'guard_name'=>'api']);
                $role->givePermissionTo($permission);
            }
        }
        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return RoleResource
     * @internal param int $id
     */
    public function show(Role $role)
    {
        return response()->json(Section::getByUserPermissions($role->permissions()->getResults(), 'menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Role $role)
    {
        return response()->json([
            'data'=>$role,
            'sections'=>Section::getByUserPermissions($role->permissions()->getResults(), 'role')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Role $role
     * @return RoleResource
     * @internal param int $id
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name'=>['required', 'string', 'max:50', 'unique:roles,name,'.$role->id],
        ]);
        $role->name = $request->name;
        if($request->get('guard_name')) $role->guard_name=$request->guard_name;
        $role->save();
        if($revoke_permissions = $request->get('revoke_permissions')){
            if (is_array($revoke_permissions)) foreach ($revoke_permissions as $permission){
                if(!Permission::where('name', '=', $permission)->first())
                    $permission = Permission::create(['name'=>$permission, 'guard_name'=>'api']);
                $role->revokePermissionTo($permission);
            }
        }
        if($give_permissions = $request->get('give_permissions')){
            if (is_array($give_permissions)) foreach ($give_permissions as $permission){
                if(!Permission::where('name', '=', $permission)->first())
                    $permission = Permission::create(['name'=>$permission, 'guard_name'=>'api']);
                $role->givePermissionTo($permission);
            }
        }
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return RoleResource
     * @internal param int $id
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json($role);
    }
}
