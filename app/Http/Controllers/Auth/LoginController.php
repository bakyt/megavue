<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function apiLogin(Request $request)
    {
        if($request->hasCookie('token')) return redirect()->route('home');
        if($request->method() == 'POST'){
            $auth = new AuthController();
            $response = $auth->login($request);
            if(!$response['status']) return redirect()->route('login')->withErrors($response['message']);
            cookie()->queue('token', $response['access_token'], $request->remember?21600:1440);
            return redirect()->route('home');
        }
        else return view('auth.login');
    }
    public function logout()
    {
        auth()->logout();
        return redirect()->route('login')->withCookie(cookie()->forget('token'));
    }
}
