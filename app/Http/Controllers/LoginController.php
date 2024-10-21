<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }else{
            return view('auth.login');
        }
    }

    public function action_login(Request $request)
    {
        if (RateLimiter::tooManyAttempts('send-message:'.$request->id, $perMinute = 5)) {
            $seconds = RateLimiter::availableIn('send-message:'.$request->id);
         
            return 'You may try again in '.$seconds.' seconds.';
        }

        $data = [
            'user_email' => $request->input('user_email'),
            'user_password' => $request->input('user_password'),
        ];

        if (Auth::Attempt($data)) {
            return redirect('dashboard');
        }else{
            Session::flash('error', 'Email atau Password Salah');
            return redirect('/');
        }
    }

    public function action_logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
