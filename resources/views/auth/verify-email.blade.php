<head>
    @vite(['resources/scss/auth.scss'])
</head>

<body>
    <div class="authContainer">
        <div class="authForm">
            <p style="text-align: center">Kiểm tra email của bạn</p>
        </div>
        <form action="{{ route('verification.send') }}" method="POST" id="resentLink">
            @csrf
            <x-common.button type="submit">
                Gửi lại link
            </x-common.button>
        </form>
    </div>
</body>
