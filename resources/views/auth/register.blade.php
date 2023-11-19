<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite(['resources/scss/register.scss'])
</head>

<body>
    <div class="registerContainer">
        <h1>Dang nhap</h1>
        @if (Session::has('success'))
            <p> {{ session::get('success') }} </p>
        @endif

        @if (Session::has('fail'))
            <p> {{ session::get('fail') }} </p>
        @endif

        <form action="{{ route('register') }}" class="registerForm" method="POST">
            @csrf
            <div class="registerField">
                <input type="text" name="email" placeholder="Email" />
                @error('email')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
            </div>

            <div class="registerField">
                <input type="text" name="name" placeholder="Ten day du" />
                @error('name')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
            </div>

            <div class="registerField">
                <input type="text" name="password" placeholder="Password" />
                @error('password')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
            </div>

            <div style="width: 100%; display: flex; flex-direction: column">
                <x-common.button type="submit">
                    Dang ky
                </x-common.button>
                <span class="register">Da co tai khoan? <a href="{{ route('login') }}">Dang nhap</a></span>
            </div>
        </form>

    </div>
</body>

</html>
