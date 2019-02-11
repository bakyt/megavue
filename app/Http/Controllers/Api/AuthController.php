<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LoginResource;
use App\Logs\Login;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PassportToken;

class AuthController extends Controller
{
    use PassportToken;
    public function login(Request $request){
        $loginLog = new Login();
        $user = User::where('username', '=', $request->username)->first();
        if($user) {
            if (md5($request->password) == $user->password) {
                $loginLog->user_id = $user->id;
                $loginLog->name = $request->username;
                $loginLog->state = 'success';
                $loginLog->time = Carbon::now();
                $loginLog->ip = $request->ip();
                $loginLog->save();
                if (!$user->getAllPermissions()) return array(
                    'message' => 'У вас нет прав',
                    'status' => false
                );
                $user->lastSeen = Carbon::now();
                $user->save();
                return response()->json(array(
                        'message' => 'Вход выполнен успешно',
                        'status' => true,
                        'permissions' => $user->getAllPermissions()->pluck('name')
                    ) + $this->getBearerTokenByUser($user, 2, false));
            }
            else {
                $loginLog->user_id = $user->id;
                $loginLog->name = $request->username;
                $loginLog->state = 'bad-password';
                $loginLog->time = Carbon::now();
                $loginLog->ip = $request->ip();
                $loginLog->save();
            }
        }
        else {
            $loginLog->name = $request->username;
            $loginLog->state = 'no-member';
            $loginLog->time = Carbon::now();
            $loginLog->ip = $request->ip();
            $loginLog->save();
        }
        return response()->json(array(
            'message' => 'Ваши учетные данные неверны. Пожалуйста, попробуйте еще раз',
            'status' => false,
            "token_type" => "",
            "expires_in" => 0,
            "access_token" => "",
            "refresh_token" => ""
        ), 200);
    }

    /**
     * For getting login history
     * param userId
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function signIns($id){
        return LoginResource::collection(Login::where('user_id', '=', $id)->orderByDesc('time')->paginate(10));
    }
}
