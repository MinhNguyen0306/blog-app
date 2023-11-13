<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite(['resources/scss/login.scss'])
</head>

<body>
    <div class="loginContainer">
        <h1>Dang nhap</h1>
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
                    <span class="errorMessage">error</span>
                @enderror
            </div>

            <div class="loginField">
                <input type="text" name="password" placeholder="Password" />
                @error('password')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
                <a href="#" class="forgotPassword">Quen mat khau?</a>
            </div>

            <div style="width: 100%; display: flex; flex-direction: column">
                <x-common.button type="submit">
                    Dang nhap
                </x-common.button>
                <span class="register">Chua co tai khoan? <a href="{{ route('register') }}">Dang ky</a></span>
            </div>
        </form>

    </div>
</body>

</html>
