<head>
    @vite(['resources/scss/auth.scss'])
</head>

<body>
    <div class="authContainer">
        <h1>Đặt lại mật khẩu</h1>
        <form class="authForm" action="{{ route('password.update') }}" method="POST">
            @csrf

            <input name="token" value={{ $token }} hidden />
            <div class="authField">
                <input type="text" name="email" placeholder="Nhập email" />
                @error('email')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
            </div>

            <div class="authField">
                <input type="text" name="password" placeholder="Nhập mật khẩu mới" />
                @error('password')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
            </div>

            <div class="authField">
                <input type="text" name="password_confirmation" placeholder="Nhập lại mật khẩu" />
                @error('password_confirmation')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
            </div>

            <div style="width: 100%; display: flex; flex-direction: column">
                <x-common.button type="submit">
                    Xác nhận
                </x-common.button>
                <a href="{{ route('home') }}">Về trang chủ</a>
            </div>
        </form>
    </div>
</body>
