<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('username', '=', $request->username)->first();
        if($user and !$user->new_password) {
            if (md5($request->password) == $user->password) {
                $user->new_password = Hash::make($request->password);
                $user->save();
            } else return response()->json(array(
                'message' => 'Ваши учетные данные неверны. Пожалуйста, попробуйте еще раз',
                'status' => false,
                "token_type" => "",
                "expires_in" => 0,
                "access_token" => "",
                "refresh_token" => ""
            ), 200);
        }
        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post($request->root() . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => '1CGKj9owQLYMXxq5CiLuqwgchfIInGuyTMRt7DUt',
                    'username' => $request->username,
                    'password' => $request->password
                ],
            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e){
            if($request->expectsJson()) {
                if ($e->getCode() == 400) return response()->json(array(
                    'message' => 'Неверный запрос. Введите имя пользователя или пароль',
                    'status' => false,
                    "token_type" => "",
                    "expires_in" => 0,
                    "access_token" => "",
                    "refresh_token" => ""
                ), 200);
                elseif ($e->getCode() == 401) return response()->json(array(
                    'message' => 'Ваши учетные данные неверны. Пожалуйста, попробуйте еще раз',
                    'status' => false,
                    "token_type" => "",
                    "expires_in" => 0,
                    "access_token" => "",
                    "refresh_token" => ""
                ), 200);
                return response()->json(array(
                    'message' => 'Что-то пошло не так на сервере',
                    'status' => false,
                    "token_type" => "",
                    "expires_in" => 0,
                    "access_token" => "",
                    "refresh_token" => ""
                ), 200);
            }
            else return array(
                'message'=>'Ваши учетные данные неверны. Пожалуйста, попробуйте еще раз',
                'status'=>false
            );
        }
        if(!$user->getAllPermissions()) return array(
            'message'=>'У вас нет прав к админке',
            'status'=>false
        );
        return array(
                'message'=>'Вход выполнен успешно',
                'status'=>true,
                'permissions'=>$user->getAllPermissions()->pluck('name')
            )+json_decode((string) $response->getBody(), true);
    }
}
