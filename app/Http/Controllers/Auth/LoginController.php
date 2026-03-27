<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
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
    protected $redirectTo = '/Admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $input = $request->all();


        $this->validate($request, [

            'email' => 'required|email',
            'password' => 'required',

        ]);

        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {

            if (Auth::user()->type == 'admin' && $input['module'] == 1) {
                return redirect('/Admin/Task');
            } else if (Auth::user()->type != 'admin' && $input['module'] == 1) {
                return redirect('/Task');
            } else {

                return redirect()->route('leads.list');

            }

        } else {

            return redirect()->route('login')

                ->with('error', 'Invalid Credentials');
        }

    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }



}
