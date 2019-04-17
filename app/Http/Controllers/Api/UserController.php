<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'unique:users'],
            'phone_code' => ['required', 'string'],
            'password' => ['required', 'string', 'max:255']
        ];
        $this->validate($request, $validate);
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->phone_code = $request->phone_code;
        $user->password = Hash::make($request->password);
        if($request->get('birth_date')) $user->birth_date = $request->birth_date;
        if($request->get('gender')) $user->gender = $request->gender;
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
        return response()->json($user);
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$id],
            'roleId' => ['required', 'integer', 'exists:roles,id']
        ];
        if($request->get('password')) $validate += ['password' => ['string', 'max:255']];
        $this->validate($request, $validate);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->get('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $oldRole = $user->roles()->first();
        if($oldRole and $request->roleId != $oldRole->id) $user->removeRole($oldRole);
        if($request->get('roleId')) $user->assignRole(Role::findById($request->get('roleId')));
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
