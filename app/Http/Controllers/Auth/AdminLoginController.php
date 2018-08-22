<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    /**
     * displays the login form
     */
    public function ShowLoginForm()
    {
        return view('auth.admin-login');
    }


    public function login(Request $request)
    {
        //validate the form data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.login')
                        ->withErrors($validator)
                        ->withInput();
        }

        //attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            //if successful, then redirect to the intended location
            return redirect()->intended(route('admin.dashboard'));
        }

        //if unsuccessful, then redirect to the login with form data
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        return redirect('/');
    }
}
