<head>
    @vite(['resources/scss/auth.scss'])
</head>

<body>
    <div class="authContainer">
        <h1>Quen mat khau</h1>
        <form action="{{ route('password.email') }}" class="authForm" method="POST">
            @csrf
            <div class="authField">
                <input type="text" name="email" placeholder="Nhap email" />
                @error('email')
                    <span class="errorMessage">{{ $message }}</span>
                @enderror
            </div>

            <div style="width: 100%; display: flex; flex-direction: column">
                <x-common.button type="submit">
                    Xac nhan
                </x-common.button>
                <a href="{{ route('get_form_login') }}">Quay lai</a>
            </div>
        </form>
    </div>
</body>
