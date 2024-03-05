<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use Cookie;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username() {
        $remember_me = request()->input('remember') ? true : false;
        $login = request()->input('login');
        $password = request()->input('password');
        
        $fieldType = filter_var($login,FILTER_VALIDATE_EMAIL) ? 'email' : 'employee_id';
        $empId = Employee::select('id')->where($fieldType,'=',$login)->value('id');

        if (empty($empId) || $empId == null) {
            return route('login');
        } else {
            $login = strval($empId);
        }
        // dd($login);
        request()->merge([$fieldType => $login]);
        
        if ($remember_me == 1) {
            unset($_COOKIE['loginUserEmail']);
            unset($_COOKIE['loginUserEmpId']);
            unset($_COOKIE['loginUserPassword']);
            unset($_COOKIE['loginUserRemember']);
            
            setcookie ("loginUserEmail",$login);
            setcookie ("loginUserEmpId",$login);
            setcookie ("loginUserPassword",$password);
            setcookie ("loginUserRemember",1);
        } else {
            unset($_COOKIE['loginUserEmail']);
            unset($_COOKIE['loginUserEmpId']);
            unset($_COOKIE['loginUserPassword']);
            unset($_COOKIE['loginUserRemember']);

            \Cookie::forget('loginUserEmail');
            \Cookie::forget('loginUserEmpId');
            \Cookie::forget('loginUserPassword');
            \Cookie::forget('loginUserRemember');
            
            setcookie('loginUserEmail', false);
            setcookie('loginUserEmpId', false);
            setcookie('loginUserPassword', false);
            setcookie('loginUserRemember', false);
        }
        
        $getStatus = User::where($fieldType,$login)->value('status');
        if (isset($getStatus) && $getStatus == 0) {
            session_unset();
            Session::flush();
            unset($_COOKIE['loginUserEmail']);
            unset($_COOKIE['loginUserEmpId']);
            unset($_COOKIE['loginUserPassword']);
            unset($_COOKIE['loginUserRemember']);
            
            \Cookie::forget('loginUserEmail');
            \Cookie::forget('loginUserEmpId');
            \Cookie::forget('loginUserPassword');
            \Cookie::forget('loginUserRemember');
            
            setcookie('loginUserEmail', false);
            setcookie('loginUserEmpId', false);
            setcookie('loginUserPassword', false);
            setcookie('loginUserRemember', false);
            
            Auth::logout();
            return route('login');
        }
        
        return $fieldType;
    }
}
