<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StoreResource;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){

        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post($request->root() . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => 'CgH64RUT176hh3Cs3MKNvf3HmPiK8kIThNlLuye0',
                    'username' => $request->phone,
                    'password' => $request->password
                ],
            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e){
            if($request->expectsJson()) {
                if ($e->getCode() == 400) return response()->json(array(
                    'message' => __('auth.bad_request'),
                    'status' => false,
                    "token_type" => "",
                    "expires_in" => 0,
                    "access_token" => "",
                    "refresh_token" => ""
                ), 200);
                elseif ($e->getCode() == 401) return response()->json(array(
                    'message' => __('auth.failed'),
                    'status' => false,
                    "token_type" => "",
                    "expires_in" => 0,
                    "access_token" => "",
                    "refresh_token" => ""
                ), 200);
                return response()->json(array(
                    'message' => $e->getMessage(),
                    'status' => false,
                    "token_type" => "",
                    "expires_in" => 0,
                    "access_token" => "",
                    "refresh_token" => ""
                ), 200);
            }
            else return array(
                'message'=>__('auth.failed'),
                'status'=>false
            );
        }
        return array(
                'message'=>__('auth.login_success'),
                'status'=>true,
//                'user'=>new UserResource($user),
//                'permissions'=>$user->getAllPermissions()->pluck('name'),
//                'stores'=>StoreResource::collection($user->stores)
            )+json_decode((string) $response->getBody(), true);
    }
    public function resetPassword(Request $request){
        $this->validate($request, [
            'phone'=> ['required', 'string', 'exists:users,phone'],
            'password'=>['required', 'string']
        ]);
        /* @var $user User */
        $user = User::where('phone', '=', $request->get('phone'))->first();
        $user->password = Hash::make($request->get('password'));
        $user->save();
    }
}
