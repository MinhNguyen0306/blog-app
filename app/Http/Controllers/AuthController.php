<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Exception;
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
            toastr()->success("Đăng nhập thành công");
            return redirect()->route('home');
        }

        toastr()->error("Email hoặc mật khẩu không đúng");
        return redirect()->back()->with([
            'fail' => "Email hoặc mật khẩu không đúng"
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $param = $request->validated();
        $param['password'] = bcrypt($param['password']);
        $user = User::create($param);

        if ($user) {
            event(new Registered($user));
            toastr()->success("Đăng ký thành công");
            return redirect()->route('home');
        }

        toastr()->error("Đăng ký thất bại");
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

        if ($status === Password::RESET_LINK_SENT) {
            toastr()->success("Đã gửi link");
            return back()->with(['status' => __($status)]);
        } else {
            toastr()->error("Email không đúng");
            return back()->withErrors(['email' => __($status)]);
        }
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

        if ($status === Password::PASSWORD_RESET) {
            toastr()->success("Đã RESET mật khẩu");
            return redirect()->route('login');
        } else {
            toastr()->error("RESET mật khẩu thất bại");
            return redirect()->back();
        }
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('home');
    }

    public function resendingVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        toastr()->success('Verification link sent!');
        return back()->with('message', 'Verification link sent!');
    }

    public function loginWithGithub()
    {
        try {
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
        } catch (Exception $e) {
            toastr($e->getMessage());
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('get_form_login');
    }
}
