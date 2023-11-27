<head>
    @vite(['resources/scss/left-content.scss'])
</head>

<div class="leftContainer">
    <x-common.logo />
    <ul class="listAction">
        <li class="action" onclick="window.location='{{ route('home') }}'">
            <i class="fa-solid fa-house icon"></i>
            <span class=" {{ Route::current('home') ? 'active' : '' }}">Trang chủ</span>
        </li>
        <li class="action">
            <i class="fa-solid fa-magnifying-glass icon"></i>
            <span>Tìm kiếm</span>
        </li>
        <li class="action" onclick="window.location='{{ route('notifications.get_notification_page') }}'">
            <i class="fa-solid fa-bell icon"></i>
            <span>Thông báo</span>
        </li>
        <li class="action">
            <i class="fa-solid fa-envelope icon"></i>
            <span>Tin nhắn</span>
        </li>
        <li class="action">
            <i class="fa-solid fa-bookmark icon"></i>
            <span>Yêu thích</span>
        </li>
        <li class="action">
            <i class="fa-solid fa-right-from-bracket icon"></i>
            <a href="{{ route('logout') }}">Đăng xuất</a>
        </li>
    </ul>

    <div class="profile" onclick="window.location='{{ route('users.get_profile_view', Auth::user()->id) }}'">
        <span class="seeAccount">Xem profile</span>
        <div class="profileContent">
            <img src={{ Auth::user()->avatar ? asset('images/' . Auth::user()->avatar) : asset('images/user.png') }}
                alt="" />
            <div class="profileInfo">
                <h3>{{ Auth::user()->name }}</h3>
                <span>{{ Auth::user()->email }}</span>
            </div>
        </div>
        <i class="fa-solid fa-ellipsis icon"></i>
    </div>
</div>
