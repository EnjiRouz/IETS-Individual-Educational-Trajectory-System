<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(){
        return view('login');
    }

    public function login(Request $request){
        $credentials = [
            'name' => $request->username,
            'password' => $request->password,
        ];

        if(Auth::validate($credentials))
        {
            Auth::login(User::where('name',$request->username)->first());

            $userId=User::where('name',$request->username)->first()->getAttribute("id");
            $adminRole=Role::where("role_name", "admin")->first()->getAttribute("id");

            // TODO role_user
            // TODO logout
            if($userId===$adminRole) {
                return view("admin");
            }
            return redirect('/');
        }
        return redirect('login');
    }

    public function logout(Request $request){
    //
    }
}
