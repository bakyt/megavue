<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PassportToken;

class AuthController extends Controller
{
    use PassportToken;
    public function login(Request $request){
        $user = User::where('username', '=', $request->username)->first();
        if($user and md5($request->password) == $user->password) {

            if(!$user->getAllPermissions()) return array(
                'message'=>'У вас нет прав',
                'status'=>false
            );
            return response()->json(array(
                    'message'=>'Вход выполнен успешно',
                    'status'=>true,
                    'permissions'=>$user->getAllPermissions()->pluck('name')
                )+$this->getBearerTokenByUser($user, 2, false));
        }
        else return response()->json(array(
            'message' => 'Ваши учетные данные неверны. Пожалуйста, попробуйте еще раз',
            'status' => false,
            "token_type" => "",
            "expires_in" => 0,
            "access_token" => "",
            "refresh_token" => ""
        ), 200);
    }
}
