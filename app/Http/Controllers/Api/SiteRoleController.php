<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SiteRoleResource;
use App\SiteRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteRoleController extends Controller
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
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if($request->has('paginate')) return SiteRoleResource::collection(SiteRole::orderByDesc('id')->paginate(10));
        return SiteRoleResource::collection(SiteRole::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return SiteRoleResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'roleName'=>['required', 'string', 'max:50', 'unique:authRoles'],
            'role_type'=>['required', 'string', 'max:255'],
            'description'=>['required', 'string', 'max:50'],
            'city'=>['required', 'string', 'max:50']
        ]);
        $siteRole = new SiteRole();
        $siteRole->roleName = $request->roleName;
        $siteRole->role_type = $request->role_type;
        $siteRole->description = $request->description;
        $siteRole->city = $request->city;
        $siteRole->save();
        return new SiteRoleResource($siteRole);
    }

    /**
     * Display the specified resource.
     *
     * @param SiteRole $siteRole
     * @return SiteRoleResource
     * @internal param int $id
     */
    public function show(SiteRole $siteRole)
    {
        return new SiteRoleResource($siteRole);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param SiteRole $siteRole
     * @return SiteRoleResource
     * @internal param int $id
     */
    public function update(Request $request, SiteRole $siteRole)
    {
        $this->validate($request, [
            'roleName'=>['required', 'string', 'max:50', 'unique:authRoles,roleName,'.$siteRole->id],
            'role_type'=>['required', 'string', 'max:255'],
            'description'=>['required', 'string', 'max:50'],
            'city'=>['required', 'string', 'max:50']
        ]);
        $siteRole->roleName = $request->roleName;
        $siteRole->role_type = $request->role_type;
        $siteRole->description = $request->description;
        $siteRole->city = $request->city;
        $siteRole->save();
        return new SiteRoleResource($siteRole);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SiteRole $siteRole
     * @return SiteRoleResource
     * @internal param int $id
     */
    public function destroy(SiteRole $siteRole)
    {
        $siteRole->delete();
        return new SiteRoleResource($siteRole);
    }
}
