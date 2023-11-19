<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite(['resources/scss/login.scss', 'resources/scss/auth.scss'])
</head>

<body>
    <div class="loginContainer">
        <h1>Đăng nhập</h1>
        @if (Session::has('success'))
            <p> {{ session::get('success') }} </p>
        @endif

        @if (Session::has('fail'))
            <p> {{ session::get('fail') }} </p>
        @endif

        <form action="{{ route('login') }}" class="loginForm" method="POST">
            @csrf
            <div class="loginField">
                <input type="text" name="email" placeholder="Email" />
                @error('email')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
            </div>

            <div class="loginField">
                <input type="text" name="password" placeholder="Mật khẩu" />
                @error('password')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
                <a href="{{ route('get_form_forgot_password') }}" class="forgotPassword">Quên mật khẩu?</a>
            </div>

            <div style="width: 100%; display: flex; flex-direction: column">
                <x-common.button type="submit">
                    Đăng nhập
                </x-common.button>
                <span class="register">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></span>
            </div>

            <div style="position: relative; height: 1px; width: 100%; background-color: gray; margin: 2rem 0">
                <span
                    style="position: absolute; top: 0; left: 50%; transform: translate(-50%, -50%);
                z-index: 50; background: #252525; font-weight: 500; padding: 0 1rem">
                    Hoặc
                </span>
            </div>

            <span class="socialLogin">
                <a href="{{ route('redirect_github') }}">
                    Đăng nhập bằng Github
                </a>
            </span>
        </form>
    </div>
</body>

</html>
