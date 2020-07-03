<?php

namespace Irfansjah\Gtx\Controller\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    protected function guard(){
        return Auth::guard('admin');
    }

    /**
     * Show the application's login form. - override trait
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $login_route = route('admin.login');
        $passwordreset_route = route('password.request');
        $register_route = route('register');

        return view('gtx::Admin.auth.login',compact('login_route','passwordreset_route', 'register_route'));
    }
}
