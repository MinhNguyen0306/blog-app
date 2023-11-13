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
        <li class="action">
            <x-ri-notification-line class="icon" />
            <span>Thong bao</span>
        </li>
        <li class="action">
            <x-fontisto-email class="icon" />
            <span>Tin nhan</span>
        </li>
        <li class="action">
            <x-heroicon-o-home class="icon" />
            <span>Ho so</span>
        </li>
        <li class="action">
            <x-heroicon-o-home class="icon" />
            <span>Yeu thich</span>
        </li>
    </ul>

    <div class="profile">
        <div class="profileContent">
            <img src={{ asset('images/Logo.svg') }} alt="" />
            <div class="profileInfo">
                <h3>Minh nguyen</h3>
                <span>@Khanguyen03062001</span>
            </div>
        </div>
        <x-majestic-more-menu-line class="icon" />
    </div>
</div>
