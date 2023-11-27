<head>
    @vite(['resources/scss/left-content.scss'])
</head>

<div class="leftContainer">
    <x-common.logo />
    <ul class="listAction">
        <li class="action">
            <x-heroicon-o-home class="icon" />
            <span class=" {{ Route::current('home') ? 'active' : '' }}">Trang chu</span>
        </li>
        <li class="action">
            <x-zondicon-search class="icon" />
            <span>Tim kiem</span>
        </li>
        <li class="action" onclick="window.location='{{ route('notifications.get_notification_page') }}'">
            <x-ri-notification-line class="icon" />
            <span>Thong bao</span>
        </li>
        <li class="action">
            <x-fontisto-email class="icon" />
            <span>Tin nhan</span>
        </li>
        <li class="action">
            <x-monoicon-favorite class="icon" />
            <span>Yeu thich</span>
        </li>
        <li class="action">
            <x-ri-logout-circle-r-line class="icon" />
            <a href="{{ route('logout') }}">Dang xuat</a>
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
        <x-majestic-more-menu-line class="icon" />
    </div>
</div>
