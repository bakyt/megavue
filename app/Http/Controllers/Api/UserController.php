<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Position;
use App\SiteRole;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::orderByDesc('id')->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'siteRoles'=>SiteRole::all(),
            'positions'=>Position::all(),
            'roles'=>Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return UserResource
     */
    public function store(Request $request)
    {
        $validate = [
            'fname'=>['required', 'string'],
            'roleId' => ['required', 'integer', 'exists:authRoles,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'sname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:authUsers'],
            'password' => ['required'],
            'position' => ['required', 'integer'],
            'readonly' => ['required', 'integer'],
            'active' => ['required', 'integer'],
        ];
        $this->validate($request, $validate);
        $user = new User();
        $user->pin = $request->pin;
        $user->name = $request->name;
        $user->sname = $request->sname;
        $user->fname = $request->fname;
        $user->username = $request->username;
        $user->roleId = $request->roleId;
        $user->post = $request->position;
        $user->email = $request->email;
        $user->readonly = $request->readonly;
        $user->active = $request->active;
        $user->password = md5($request->password);
        $user->new_password = Hash::make($request->password);
        $user->lastSeen = Carbon::now();
        $user->save();
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return UserResource
     * @internal param int $id
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'siteRoles'=>SiteRole::all(),
            'positions'=>Position::all(),
            'roles'=>Role::all(),
            'user'=>new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $id
     * @return UserResource
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validate = [
            'fname'=>['required', 'string'],
            'roleId' => ['required', 'integer', 'exists:authRoles,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'sname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:authUsers,username,'.$id],
            'position' => ['required', 'integer'],
            'readonly' => ['required', 'integer'],
            'active' => ['required', 'integer'],
        ];
        if($request->get('password')) $validate += ['password' => ['string', 'max:255']];
        $this->validate($request, $validate);
        $user->pin = $request->pin;
        $user->name = $request->name;
        $user->sname = $request->sname;
        $user->fname = $request->fname;
        $user->username = $request->username;
        $user->roleId = $request->roleId;
        $user->post = $request->position;
        $user->email = $request->email;
        $user->readonly = $request->readonly;
        $user->active = $request->active;
        if($request->get('password')) {
            $user->password = md5($request->password);
            $user->new_password = Hash::make($request->password);
        }
        $user->save();
        if($request->user()->hasPermissionTo('tool-users.edit') and $request->get('role_name')){
            $oldRole = $user->roles()->first();
            if($oldRole and $request->role_name != $oldRole->name) $user->removeRole($oldRole);
            if($request->get('role_name')) $user->assignRole($request->role_name);
        }
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return UserResource
     * @internal param int $id
     */
    public function destroy(User $user)
    {
        $user->delete();
        return new UserResource($user);
    }
}
