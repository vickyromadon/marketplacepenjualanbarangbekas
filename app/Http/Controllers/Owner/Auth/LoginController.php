<?php

namespace App\Http\Controllers\Owner\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:owner')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if( $request->isMethod('post') ){
            $validator = Validator::make( $request->all(),[
                    'email' => 'required|email',
                    'password' => 'required|min:6'
                ] 
            );

            $credential = [
                'email' => $request->email,
                'password' => $request->password
            ];
            
            if( Auth::guard('owner')->attempt($credential, $request->member) ){
                return redirect()->intended(route('owner.home'));
            }
            else{
                $validator->after(function ($validator) {
                    $validator->errors()->add('email', 'Failed, your credential not match with our database!');
                });
            }

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                return redirect()->back()->withInput($request->only('email', 'remember'));
            }
        }

        return view('owner.auth.login');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::guard('owner')->logout();
        return redirect()->route('owner.login');
    }
}
