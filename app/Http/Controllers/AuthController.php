<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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

    public function getFormForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function getFormResetPassword(String $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function getVerificationNotice()
    {
        return view('auth.verify-email');
    }

    public function getRedirectGithubLogin()
    {
        return Socialite::driver('github')->redirect();
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
            event(new Registered($user));
            return redirect()->route('home');
        }

        return redirect()->back()->with([
            'fail' => 'Tao user that bai'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => "required|email"]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        // $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('home');
    }

    public function resendingVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }

    public function loginWithGithub()
    {
        $githubUser = Socialite::driver('github')->user();

        $userExisted = User::where('oauth_id', $githubUser->id)->first();

        if ($userExisted) {
            Auth::login($userExisted);
        } else {
            $user = User::create([
                'oauth_id' => $githubUser->id,
                'name' => $githubUser->name,
                'email' => $githubUser->email,
                'password' => Hash::make('password_hash'),
                'oauth_token' => $githubUser->token,
                'oauth_refresh_token' => $githubUser->refreshToken,
            ]);

            Auth::login($user);
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('get_form_login');
    }
}
