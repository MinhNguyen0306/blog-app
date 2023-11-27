<head>
    @vite(['resources/scss/right-content.scss'])
</head>

<div class="rightContainer">
    <div class="rightHeader">
        <div class="searchBox">
            <input type="text" placeholder="Tìm kiếm" />
            <i class="fa-solid fa-magnifying-glass icon"></i>
        </div>
    </div>

    <div class="rightContent">
        <div class="registerPremium">
            <h2>Đăng ký hội viên</h2>
            <p>Đăng ký để mở khóa các tính năng mới và nếu đủ điều kiện, bạn sẽ được nhận một khoản chia sẻ doanh thu từ
                quảng cáo.</p>
            {{-- <form action="{{ route('payment.get_payment_package_view') }}" method="POST" style="width: 100%">
                @csrf --}}
            <x-common.button type="button" name="redirect"
                onclick="window.location='{{ route('payment.get_payment_package_view') }}'">
                Đăng ký
            </x-common.button>
            {{-- </form> --}}
        </div>

        <div class="trend">

        </div>

        <div class="hintFollow">
            <h2>Gợi ý theo dõi</h2>
            <ul class="listHint">
                <li class="userContainer">
                    @foreach ($users as $user)
                        <div class="userInfo">
                            <img onclick="window.location='{{ route('users.get_profile_view', $user->id) }}'"
                                src={{ $user->avatar ? asset('images/' . $user->avatar) : asset('images/user.png') }}
                                alt="user image" />
                            <div class="userName">
                                <h3>{{ $user->name }}</h3>
                                <span class="userEmail">{{ $user->email }}</span>
                            </div>
                        </div>
                    @endforeach
                    <div>
                        <x-common.button type="button">
                            Follow
                        </x-common.button>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>
