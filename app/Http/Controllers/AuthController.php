<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getFormLogin()
    {
        return view('auth.login');
    }

    public function getFormRegister()
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        return redirect()->back()->with([
            'fail' => "Sai email hoac mat khau"
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $param = $request->validated();
        $param['password'] = bcrypt($param['password']);
        $user = User::create($param);

        if ($user) {
            return redirect()->route('home');
        }

        return redirect()->back()->with([
            'fail' => 'Tao user that bai'
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('get_form_login');
    }
}
